CREATE TABLE users (
    USER_ID INT PRIMARY KEY AUTO_INCREMENT,
    VORNAME VARCHAR(100) NOT NULL, 			--vielleicht zu kurz?
    NACHNAME VARCHAR(100) NOT NULL,  		--vielleicht zu kurz?
    E_MAIL VARCHAR(100) UNIQUE NOT NULL, 	--einmalig wichtig als Indikator
    PASSWORT_HASH VARCHAR(255) NOT NULL, 	--Funktion muss eingefügt werden, die das Passwort hasht
    ROLE VARCHAR(30) NOT NULL 				--Mieter, Vermieter, ADMIN
);

ALTER TABLE users CHANGE VORNAME forname VARCHAR(255);
ALTER TABLE users CHANGE NACHNAME surname VARCHAR(255);
ALTER TABLE users CHANGE E_MAIL email VARCHAR(255);
ALTER TABLE users CHANGE PASSWORT_HASH password VARCHAR(255);
ALTER TABLE users CHANGE ROLE role VARCHAR(50);


CREATE TABLE haus (  --Tags und Bilder sind in andere Tabellen ausgelagert
    HAUS_ID INT PRIMARY KEY AUTO_INCREMENT,
    NAME VARCHAR(255) NOT NULL,
    PERSONEN INT NOT NULL,
    LAND VARCHAR(100) NOT NULL,
    ADRESSE VARCHAR(255) NOT NULL,
    PREIS DECIMAL(10, 2) NOT NULL, -- PREIS als Dezimalzahl, um den Preis in € darzustellen
    BESCHREIBUNG TEXT NOT NULL,
    USER_ID INT NOT NULL,
	FOREIGN KEY (USER_ID) REFERENCES users(USER_ID)
);

CREATE TABLE img ( -- Tabelle für die Speicherung der Bilder
	IMG_ID INT PRIMARY KEY AUTO_INCREMENT,
	IMG_URL VARCHAR(255) NOT NULL, --Bilddatei wird nicht gespeichert, sondern auf dem Server abgelegt und via URL referenziert
    IMG_TYP VARCHAR(255) NOT NULL, --Bild Typ wird extra angegeben für Außenansicht, Innenansicht und Lageplan etc.
	HAUS_ID INT NOT NULL, --Wird mit Fremdschlüssel an Häuser gebunden und mit Joins kombiniert
	FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID)
);

CREATE TABLE tags (
    TAG_ID INT PRIMARY KEY AUTO_INCREMENT,
    TAG_WERT VARCHAR(255) UNIQUE NOT NULL
);


CREATE TABLE buchungen ( --Buchungen werden mit Anreise und Abreisedatum hinterlegt und -
	BUCHUNG_ID INT PRIMARY KEY AUTO_INCREMENT, -- der Preis wird über ein Join mit dem Preis des Hauses -
	ARRIVAL DATE NOT NULL, --berechnet und der Anzahl der Tage zwischen Anreise und Abreise.
	DEPARTURE DATE NOT NULL,
    USER_ID INT NOT NULL,
    HAUS_ID INT NOT NULL,
	FOREIGN KEY (USER_ID) REFERENCES users(USER_ID),
    FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID)
);

CREATE TABLE tag_haus_relation ( --Aufgrund der N:N Beziehung bei Tags muss eine Relationstabelle generiert werden
	HAUS_ID INT NOT NULL,
	TAG_ID INT NOT NULL,
	FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID),
	FOREIGN KEY (TAG_ID) REFERENCES tags(TAG_ID)
);


