# main.py

from fastapi import FastAPI, HTTPException, Depends
import sqlite3, os
from pydantic import BaseModel
from fastapi.security import HTTPBasic, HTTPBasicCredentials

app = FastAPI()
security = HTTPBasic()
session_active = False  # Variable de session

fake_users_db = {
    "testuser": {
        "username": "testuser",
        "password": "testpassword"
    }
}

def authenticate_user(credentials: HTTPBasicCredentials = Depends(security)):
    user = fake_users_db.get(credentials.username)
    if user is None or user["password"] != credentials.password:
        raise HTTPException(
            status_code=401,
            detail="Invalid credentials",
            headers={"WWW-Authenticate": "Basic"},
        )
    return user

def check_session():
    if not session_active:
        raise HTTPException(status_code=401, detail="Pas de session")


@app.get("/login")
def login(credentials: HTTPBasicCredentials = Depends(authenticate_user)):
    global session_active
    session_active = True  # Activer la session lors de la connexion
    return {"message": "Login successful"}

def get_db_connection():
    conn = sqlite3.connect(f'Projet/Fleurs/Fleurs.sqlite')
    conn.row_factory = sqlite3.Row
    return conn

# Route (endpoint) avec la méthode GET pour obtenir tous les produits (fleurs)
@app.get("/produits/")
def get_produits():
    check_session()  # Vérifier la session avant d'exécuter la fonction
    conn = get_db_connection()
    try:
        # Utilisez le nom du conteneur PostgreSQL dans le réseau Docker comme alias dans la connexion
        with get_db_connection() as conn:
            cur = conn.cursor()
            produits = cur.execute('SELECT * FROM produit').fetchall()
            if not produits:
                raise HTTPException(status_code=404, detail="Aucun produit trouvé")
    except sqlite3.Error as error:
        print("Erreur Lecture SQLite", error)
    if conn:
        conn.close()
        print("connexion fermé")
    return produits

# Modèle Pydantic pour la création de produit
class ProduitCreate(BaseModel):
    pdt_ref: str
    pdt_designation: str
    pdt_prix: float
    pdt_image: str
    pdt_categorie: int

# Route (endpoint) avec la méthode POST pour créer un produit
@app.post("/produits/creer")
def create_produit(produit: ProduitCreate):
    check_session()  # Vérifier la session avant d'exécuter la fonction
    try:
        with get_db_connection() as conn:
            cur = conn.cursor()
            cur.execute('INSERT INTO produit (pdt_ref, pdt_designation, pdt_prix, pdt_image, pdt_categorie) VALUES (?, ?, ?, ?, ?)',
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

# Route (endpoint) avec la méthode DELETE pour supprimer un produit par référence
@app.delete("/produits/supprimer/{pdt_ref}")
def delete_produit(pdt_ref: str):
    check_session()  # Vérifier la session avant d'exécuter la fonction
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

# Route (endpoint) avec la méthode PUT pour mettre à jour un produit par référence
@app.put("/produits/maj/{pdt_ref}")
def update_produit(pdt_ref: str, produit_update: ProduitCreate):
    check_session()  # Vérifier la session avant d'exécuter la fonction
    try:
        with get_db_connection() as conn:
            cur = conn.cursor()
            cur.execute('UPDATE produit SET pdt_designation = ?, pdt_prix = ?, pdt_image = ?, pdt_categorie = ? WHERE pdt_ref = ?',
                        (produit_update.pdt_designation, produit_update.pdt_prix, produit_update.pdt_image, produit_update.pdt_categorie, pdt_ref))
            conn.commit()
    except sqlite3.Error as error:
        print("Erreur Mise à Jour SQLite", error)
        raise HTTPException(status_code=500, detail="Erreur lors de la mise à jour du produit")
    finally:
        if conn:
            conn.close()
            print("Connection fermée")
    return {"message": "Produit mis à jour"}

