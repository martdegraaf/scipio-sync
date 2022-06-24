# Scipio sync with Gsuite emailadressen

Endpoint om huidige uit Scipio in file te zetten evenals die van gsuite. (gsuite moet nog zelf eerst in configuratiebestand google-routing-current.json worden geplaatst)

## Configuratie

Rename `settings.template.json` naar `settings.json` en vul de benodigde settings in.

# Stappenplan

1. Verbind via SSH met de website 'gkvhetlichtpunt.nl'
2. Ga naar de folder `cd /opt/scipiosync/scipio-sync-main`
3. Haal de huidige emailadressen via F12 uit de source van de Gsuite. en zet deze in `google-routing-current.json`
4. Voer de compare uit `php8.1 run_compare.php`
5. Doe bulk import in Gsuite met de lijst `diff-to-add-comma-seperated.txt` om de gemiste records toe te voegen.
6. Remove alle emailadressen die gemeld staan in `diff-to-remove.json` maak ze mooi door dit command: `jq . diff-to-remove.json`.


# Notities

Output van script:

1. `scipio-gsuite-mailinglijst.json` => Scipio emailadressen in JSON format.
2. `diff-to-add.json` => Emailadressen die in Gsuite zouden moeten worden **toegevoegd**
2. `diff-to-remove.json` => Emailadressen die in Gsuite zouden moeten worden **verwijderd**
3. `diff-to-add-comma-seperated.txt`  => Emailadressen die in Gsuite zouden moeten worden **toegevoegd** in het formaat die wordt geaccepteerd door de bulk import van google
5. `gsuite-mailinglijst.txt` => text representatie van Scipio emailadressen **wijk-emailadres,persoon-emailadres**
6. `gsuite-mailinglijst-actual.txt` => text representatie van gsuite actual **wijk-emailadres,persoon-emailadres**

