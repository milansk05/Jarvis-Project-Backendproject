# Bit-Tweets Website

Deze repository bevat de broncode voor de Bit-Tweets website. Bit-Tweets is een platform voor gebruikers om korte berichten te delen, vergelijkbaar met tweets op Twitter.

## Functionaliteiten
### 1. Inloggen en Authenticatie:

Gebruikers moeten inloggen om berichten te kunnen plaatsen.
Authenticatie wordt afgehandeld door auth.php.

### 2. Database Integratie:

De website maakt gebruik van een database voor het opslaan van berichten en gebruikersgegevens.
Database-inloggegevens worden beheerd in database_login.php.

### 3. Berichten Weergeven:

Berichten worden opgehaald uit de database en weergegeven op de homepage (index.php).
Berichten zijn gesorteerd op vastgezette berichten en vervolgens op datum en tijd.

### 4. Bericht Plaatsen:

Gebruikers kunnen nieuwe berichten plaatsen via een formulier op de homepage.
Er is een cooldown van 10 seconden tussen het plaatsen van berichten.

### 5. Bericht Limitering:

Berichten worden gelimiteerd tot 500 tekens.
Indien een bericht langer is, wordt het automatisch ingekort.

### 6. Gepinde Berichten:

Gebruikers kunnen berichten vastzetten, die vervolgens op een aparte sidebar worden weergegeven.

### 7. Sidebar en Navigatie:

De website heeft een in/uitklapbare sidebar met navigatielinks.
De sidebar bevat links naar de homepagina, ledenlijst, profielpagina, uitloggen, changelog en de admin-pagina (alleen zichtbaar voor admins).

### 8. Profielpagina:

Gebruikers kunnen hun profielpagina bekijken en bewerken via profile.php.
De profielfoto, gebruikersnaam, en rang worden weergegeven.

### 9. Admin Pagina:

Er is een speciale admin-pagina (admin.php) toegankelijk voor beheerders.
Op de admin-pagina worden extra functies en informatie getoond.

### 10. Styling:

De website maakt gebruik van Tailwind CSS voor de styling.
De stijl is eenvoudig, maar functioneel en responsief.


## Hoe te Gebruiken
1. Zorg ervoor dat een PHP-server is ingesteld.
2. Maak een database aan en voer de SQL-code uit van database.sql om de benodigde tabellen aan te maken.
3. Vul de database-inloggegevens in in database_login.php.
4. Plaats de bestanden op de server.
5. Bezoek de website en log in om de functionaliteiten te gebruiken.

## Overige informatie
- Opmerking: Deze readme gaat ervan uit dat er al enige basiskennis is van webontwikkeling, PHP en databases.

- De homepagina is niet mobile-first

- Deze website is ook bereikbaar op het internet. Bezoek ***milansnoeijink.nl*** om daar een volledig voorbeeld van de functionaliteiten te bereiken