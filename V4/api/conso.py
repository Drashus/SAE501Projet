from fastapi import Depends, FastAPI, HTTPException
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
import jwt
from pydantic import BaseModel
import sqlite3

# Clé secrète pour signer le token
SECRET_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiLCJzdWIiOiJ2aXNpdGV1ciIsImF1ZCI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSIsImlhdCI6MTcwMjgzNTY1NiwiZXhwIjoxNzU2ODM1NjU2LCJub20iOiJ2aXNpdGV1ciIsInJvbGUiOiJ2aXNpdGV1ciJ9.sRaSYM5hSVxMd3whwaup8HSV1siEGSbZYt4QpjgxTeo"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 900000

# Création d'un objet HTTPBearer pour gérer la sécurité via un token
security = HTTPBearer()

app = FastAPI()

# Fonction qui permet la validation du Token qui dépend de l'objet security
def verifierTokenAcces(credits: HTTPAuthorizationCredentials = Depends(security)):
    token = credits.credentials  # On récupère le token
    try:
        payload = jwt.decode(
            # On décode le token pour récupérer le payload
            token,
            SECRET_KEY,
            algorithms=['HS256'],
            options={"verify_aud": False, "verify_iat": False}
        )
        if payload is None:
            raise HTTPException(status_code=401, detail='Could not validate credentials')
        return payload  # On retourne le payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail='Token expired')
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail='Invalid token')

# Routes qui nécessitent un token avec la dépendance verifierTokenAcces
@app.get("/private")
async def routePrivee(payload=Depends(verifierTokenAcces)):
    return {"Accès privé": True, "valeurs": payload}


def check_session(payload=Depends(verifierTokenAcces)):
    session_active = True  # Variable de session
    if not session_active:
        raise HTTPException(status_code=401, detail="Pas de session")


def get_db_connection():
    conn = sqlite3.connect(f'../Fleurs/Fleurs.sqlite')
    conn.row_factory = sqlite3.Row
    return conn


@app.get("/produits/")
def get_produits(payload=Depends(verifierTokenAcces)):
    check_session() # Vérifie si il y a une session
    conn = get_db_connection()
    try:
        cur = conn.cursor()
        produits = cur.execute('SELECT * FROM produit').fetchall()
        if not produits:
            raise HTTPException(status_code=404, detail="Aucun produit trouvé")
    except sqlite3.Error as error:
        print("Erreur Lecture SQLite", error)
    finally:
        if conn:
            conn.close()
            print("Connection fermée")
    return produits


class ProduitCreate(BaseModel):
    pdt_ref: str
    pdt_designation: str
    pdt_prix: float
    pdt_image: str
    pdt_categorie: int


@app.post("/produits/creer")
def create_produit(produit: ProduitCreate, payload=Depends(verifierTokenAcces)):
    check_session()
    try:
        with get_db_connection() as conn:
            cur = conn.cursor()
            cur.execute(
                'INSERT INTO produit (pdt_ref, pdt_designation, pdt_prix, pdt_image, pdt_categorie) VALUES (?, ?, ?, ?, ?)',
                (produit.pdt_ref, produit.pdt_designation, produit.pdt_prix, produit.pdt_image, produit.pdt_categorie))
            conn.commit()
            produit_id = cur.lastrowid
    except sqlite3.Error as error:
        print("Erreur Insertion SQLite", error)
        raise HTTPException(status_code=500, detail="Erreur lors de la création du produit")
    finally:
        if conn:
            conn.close()
            print("Connection fermée")
    return {"message": "Produit créé", "produit_id": produit_id}


@app.delete("/produits/supprimer/{pdt_ref}")
def delete_produit(pdt_ref: str, payload=Depends(verifierTokenAcces)):
    check_session()
    try:
        with get_db_connection() as conn:
            cur = conn.cursor()
            cur.execute('DELETE FROM produit WHERE pdt_ref = ?', (pdt_ref,))
            conn.commit()
    except sqlite3.Error as error:
        print("Erreur Suppression SQLite", error)
        raise HTTPException(status_code=500, detail="Erreur lors de la suppression du produit")
    finally:
        if conn:
            conn.close()
            print("Connection fermée")
    return {"message": "Produit supprimé"}


@app.put("/produits/maj/{pdt_ref}")
def update_produit(pdt_ref: str, produit_update: ProduitCreate, payload=Depends(verifierTokenAcces)):
    check_session()
    try:
        with get_db_connection() as conn:
            cur = conn.cursor()
            cur.execute(
                'UPDATE produit SET pdt_designation = ?, pdt_prix = ?, pdt_image = ?, pdt_categorie = ? WHERE pdt_ref = ?',
                (produit_update.pdt_designation, produit_update.pdt_prix, produit_update.pdt_image,
                 produit_update.pdt_categorie, pdt_ref))
            conn.commit()
    except sqlite3.Error as error:
        print("Erreur Mise à Jour SQLite", error)
        raise HTTPException(status_code=500, detail="Erreur lors de la mise à jour du produit")
    finally:
        if conn:
            conn.close()
            print("Connection fermée")
    return {"message": "Produit mis à jour"}

