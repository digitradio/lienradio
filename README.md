# Lecteur MP3 en Ligne (DigitRadio)

Hébergement de fichiers audio MP3 avec lecteur direct en ligne via **GitHub Pages**.

## 🚀 Comment ça marche ?

1. Déposez vos fichiers `.mp3` dans le dossier `mp3/`.
2. Le flux GitHub Actions met automatiquement à jour la liste dans `playlist.json`.
3. Rendez-vous sur votre site GitHub Pages pour écouter directement vos fichiers audio et récupérer leurs liens directs !

## 📂 Déposer de nouveaux MP3

### Option 1 : Directement sur l'interface GitHub
1. Ouvrez votre dépôt sur GitHub : [digitradio/lienradio](https://github.com/digitradio/lienradio).
2. Rendez-vous dans le dossier `mp3/`.
3. Cliquez sur **Add file** > **Upload files**.
4. Glissez-déposez vos fichiers MP3 puis validez avec **Commit changes**.
5. C'est tout ! Le fichier sera disponible immédiatement.

### Option 2 : En ligne de commande (Git)
```bash
cp /chemin/vers/votre_son.mp3 ./mp3/
git add mp3/
git commit -m "Ajout de nouveau mp3"
git push origin main
```

## ⚙️ Activer GitHub Pages

1. Allez dans les réglages de votre dépôt : **Settings** > **Pages**.
2. Dans **Build and deployment** :
   - **Source** : `Deploy from a branch`
   - **Branch** : `main` (dossier `/ (root)`)
3. Cliquez sur **Save**.
4. Votre site sera disponible à l'adresse :  
   `https://digitradio.github.io/lienradio/`
