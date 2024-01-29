import requests
# Première partie: on récupère un token sur l'API "User"
url = "http://127.0.0.1:8000/user/login"
donnees = {
    "pseudo": "admin",
    "pass_": "admin"
}
response = requests.post(url, json=donnees)

if response.status_code == 200:
    data = response.json()
    token = data['access_token']
    print(token)  # on affiche le token

    # Deuxième partie: on appelle la route privée de l'API "Test"
    url = "http://127.0.0.1:8001/private"

    # Le client doit passer le jeton dans l'entête HTTP
    headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    }

    response = requests.get(url, headers=headers)
    print(response.text)  # on affiche le retour de la route /private
else:
    print(f"Request failed with status code: {response.status_code}\nResponse text: {response.text}")

