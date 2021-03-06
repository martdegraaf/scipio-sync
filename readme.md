# Scipio sync with Gsuite emailadressen

Endpoint om huidige uit Scipio in file te zetten evenals die van gsuite. (gsuite moet nog zelf eerst in configuratiebestand google-routing-current.json worden geplaatst)

## Configuratie
1. Rename `settings.template.json` naar `settings.json` en vul de benodigde settings in.
2. Rename `google-routing-current.template.json` naar `google-routing-current.json` en vul deze met de huidige data uit de Gsuite-lijst.

```cmd
cp settings.template.json settings.json
cp google-routing-current.template.json google-routing-current.json
```

# Stappenplan Sync

1. Verbind via SSH met de website 
2. Zet deze repository in een folder neer op de server.
3. Zorg dat je in de folder `cd /opt/scipiosync/scipio-sync-main` zit.
4. Haal de huidige emailadressen via F12 uit de source van de Gsuite. en zet deze in `config/google-routing-current.json`
5. Voer de compare uit `php8.1 run_compare.php`
6. Doe bulk import in Gsuite met de lijst `diff-to-add-comma-seperated.txt` om de gemiste records toe te voegen.
7. Remove alle emailadressen die gemeld staan in `diff-to-remove.json` maak ze mooi door dit command: `jq . diff-to-remove.json`.


# Notities

Output van script:

1. `scipio-gsuite-mailinglijst.json` => Scipio emailadressen in JSON format.
2. `diff-to-add.json` => Emailadressen die in Gsuite zouden moeten worden **toegevoegd**
2. `diff-to-remove.json` => Emailadressen die in Gsuite zouden moeten worden **verwijderd**
3. `diff-to-add-comma-seperated.txt`  => Emailadressen die in Gsuite zouden moeten worden **toegevoegd** in het formaat die wordt geaccepteerd door de bulk import van google
5. `gsuite-mailinglijst.txt` => text representatie van Scipio emailadressen **wijk-emailadres,persoon-emailadres**
6. `gsuite-mailinglijst-actual.txt` => text representatie van gsuite actual **wijk-emailadres,persoon-emailadres**

# Classes

``` mermaid
sequenceDiagram

    Main->>ScipioOnline: extract
    ScipioOnline->>Main: returns Array of emailadresses
    Main->>GsuiteCompare: extract
    GsuiteCompare->>Main: returns Array of emailadresses
    
    Main->>GsuiteCompare: compare scipio agains Gsuite
    Note right of GsuiteCompare: write output to diff-to-add.json
    Main->>GsuiteCompare: compare Gsuite agains scipio
    Note right of GsuiteCompare: write output to diff-to-remove.json
```