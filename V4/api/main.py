from fastapi import FastAPI, HTTPException, Depends
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from pydantic import BaseModel
from datetime import datetime, timedelta
import sqlite3
import jwt

# Chemin vers la base de données SQLite
DATABASE_PATH = "../Fleurs/user.sqlite"

# Clé secrète pour signer le token
SECRET_KEY ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiLCJzdWIiOiJ2aXNpdGV1ciIsImF1ZCI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSIsImlhdCI6MTcwMjgzNTY1NiwiZXhwIjoxNzU2ODM1NjU2LCJub20iOiJ2aXNpdGV1ciIsInJvbGUiOiJ2aXNpdGV1ciJ9.sRaSYM5hSVxMd3whwaup8HSV1siEGSbZYt4QpjgxTeo"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 900000

# Création d'une application FastAPI
app = FastAPI()
# Modèle Pydantic pour l'utilisateur et le mot de passe
class LoginInput(BaseModel):
    pseudo: str
    pass_: str  # Le mot de passe

# Modèle Pydantic pour la création d'un token
class AccessToken(BaseModel):
    access_token: str
    token_type: str

# Fonction pour créer un token associé à un utilisateur authentifié
def creerAccessToken(user):
    verification_result = verifierUser(user)

    if verification_result:
        role = verification_result["role"]

    payload = {
        "iss": "http://127.0.0.1:8000",
        "sub": user["pseudo"],
        "aud": "http://127.0.0.1:8001",
        "iat": datetime.now(),
        "exp": datetime.now() + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES),
        "nom": user["pseudo"],
        "role": role
    }

    access_token = jwt.encode(payload, SECRET_KEY, algorithm=ALGORITHM)
    return {"access_token": access_token, "token_type": "bearer"}

# Fonction pour vérifier l'utilisateur
def verifierUser(user):
    retour = {"user_found": False, "role": None}  # Utilisation d'un dictionnaire
    conn = sqlite3.connect(DATABASE_PATH)
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM utilisateurs WHERE pseudo = ? AND pass = ?", (user["pseudo"], user["pass_"]))
    result = cursor.fetchone()
    if result:
        retour["user_found"] = True
        retour["role"] = result[2]  # Assurez-vous que l'index 2 est correct
    conn.close()
    return retour



# Route pour l'authentification
@app.post("/user/login", response_model=AccessToken)
async def logguerUser(login_input: LoginInput):
    if verifierUser(login_input.dict()):
        return creerAccessToken(login_input.dict())
    else:
        raise HTTPException(status_code=401, detail="Erreur de login")

