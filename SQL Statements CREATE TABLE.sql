CREATE TABLE users (
    USER_ID INT PRIMARY KEY AUTO_INCREMENT,
    VORNAME VARCHAR(100) NOT NULL, 			
    NACHNAME VARCHAR(100) NOT NULL,  		
    E_MAIL VARCHAR(100) UNIQUE NOT NULL, 
    PASSWORT_HASH VARCHAR(255) NOT NULL, 	
    ROLE VARCHAR(30) NOT NULL 				
);

CREATE TABLE haus ( 
    HAUS_ID INT PRIMARY KEY AUTO_INCREMENT,
    NAME VARCHAR(255) NOT NULL,
    PERSONEN INT NOT NULL,
    LAND VARCHAR(100) NOT NULL,
    ADRESSE VARCHAR(255) NOT NULL,
    PREIS DECIMAL(10, 2) NOT NULL, 
    BESCHREIBUNG TEXT NOT NULL,
    USER_ID INT NOT NULL,
	FOREIGN KEY (USER_ID) REFERENCES users(USER_ID)
);

CREATE TABLE img ( 
	IMG_ID INT PRIMARY KEY AUTO_INCREMENT,
	IMG_URL VARCHAR(255) NOT NULL, 
    IMG_TYP VARCHAR(255) NOT NULL, 
	HAUS_ID INT NOT NULL, 
	FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID)
);

CREATE TABLE tags (
    TAG_ID INT PRIMARY KEY AUTO_INCREMENT,
    TAG_WERT VARCHAR(255) UNIQUE NOT NULL
);


CREATE TABLE buchungen ( 
	BUCHUNG_ID INT PRIMARY KEY AUTO_INCREMENT, 
	ARRIVAL DATE NOT NULL,
	DEPARTURE DATE NOT NULL,
    USER_ID INT NOT NULL,
    HAUS_ID INT NOT NULL,
	FOREIGN KEY (USER_ID) REFERENCES users(USER_ID),
    FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID)
);

CREATE TABLE tag_haus_relation ( 
	HAUS_ID INT NOT NULL,
	TAG_ID INT NOT NULL,
	FOREIGN KEY (HAUS_ID) REFERENCES haus(HAUS_ID),
	FOREIGN KEY (TAG_ID) REFERENCES tags(TAG_ID)
);

ALTER TABLE users CHANGE VORNAME forname VARCHAR(255);
ALTER TABLE users CHANGE NACHNAME surname VARCHAR(255);
ALTER TABLE users CHANGE E_MAIL email VARCHAR(255);
ALTER TABLE users CHANGE PASSWORT_HASH password VARCHAR(255);
ALTER TABLE users CHANGE ROLE role VARCHAR(50);

ALTER TABLE haus
    CHANGE COLUMN NAME name VARCHAR(255) NOT NULL,
    CHANGE COLUMN PERSONEN personen INT NOT NULL,
    CHANGE COLUMN LAND land VARCHAR(100) NOT NULL,
    CHANGE COLUMN ADRESSE adresse VARCHAR(255) NOT NULL,
    CHANGE COLUMN PREIS preis DECIMAL(10, 2) NOT NULL,
    CHANGE COLUMN BESCHREIBUNG beschreibung TEXT NOT NULL;

ALTER TABLE img
    CHANGE COLUMN IMG_URL img_url VARCHAR(255) NOT NULL,
    CHANGE COLUMN IMG_TYP img_typ VARCHAR(255) NOT NULL,

ALTER TABLE buchungen
	CHANGE COLUMN ARRIVAL arrival DATE NOT NULL,
    CHANGE COLUMN DEPARTURE departure DATE NOT NULL;

ALTER TABLE buchungen
	ADD gesamtpreis DECIMAL(10, 2) NOT NULL;

ALTER TABLE tags 
	CHANGE COLUMN TAG_WERT tag_wert VARCHAR(255) UNIQUE NOT NULL;

INSERT INTO tags (tag_wert) 
VALUES ('Bergblick'),('Strand'), ('Pool'), ('WLAN'), ('Strandnähe'), ('Haustiere erlaubt'), ('Balkon'), ('Garten/Terasse'), ('Klimaanlage'), ('Küche'),('Waschmaschine'), ('Grill'), ('Bettwäsche'), ('Meerblick'), ('Parkplatz'), ('Nichtraucher'), ('Allergikerfreundlich'), ('Kinderfreundlich'), ('Familienfreundlich'), ('Barrierefrei');
	
-- Anzahl Ferienhäuser für Initialsuche von index Seite
SELECT COUNT(HAUS_ID)
FROM haus
WHERE land='xxx' AND personen=xx;

-- Ausgabe Ferienhäuser für Initialsuche von index Seite	
SELECT HAUS_ID
FROM haus
WHERE land='xxx' AND personen=xx;

-- Alle Daten der gesuchten Ferienhäuser anhand der ID's in einer neuen Abfrage laden (Soll in PHP dann in einzelnen Containern angezeigt werden)



-- Tags werden über Checkboxen angeklickt und mit Suche aktualisieren wird eine neue Anfrage durchgeführt und alle nur noch angezeigt.
-- Es werden alle HAUS_ID's von der ersten Abfrage im Array genommen und die TAG_ID von allen Tags, die angewählt wurden. 
-- Dann wird geprüft, welche Kombination in der Relationstabelle ist und nur diese werden dann wieder selektiert und ausgegeben oder vielleicht nur ausgeschlossen


-- Wenn man ein Ferienhaus öffnet, werden auch alle restlichen Daten geladen und angezeigt.
SELECT xx
FROM haus
WHERE

SELECT xx
FROM img
WHERE

SELECT xx
FROM tags
WHERE

-- Buchungen werden in die Datenbank geschrieben, dazu wird das Anreise und Abreisedatum neu eingegeben, daraus wird der Preis berechnet, danach werden die Daten ausgegeben
INSERT INTO buchungen (arrival, departure, gesamtpreis, HAUS_ID, USER_ID)
VALUES (?, ?, ?, ?, ?);

SELECT * FROM buchungen
WHERE VALUES = (?, ?, ?, ?, ?);

-- Buchungen anzeigen aus Mieter Perspektive
SELECT * FROM buchungen
WHERE USER_ID=?;

-- Profil Daten anzeigen für Mieter und Vermieter
SELECT * FROM users
WHERE USER_ID=?;

-- Geänderte Profildaten in der Datenbank speichern, Email muss UNIQUE Check geben, Passwort muss altes Passwort eingegeben werden und geprüft werden
UPDATE users
SET forname = ?, surname = ?, email = ?, password_hash = ?
WHERE USER_ID = ?;

-- Häuser erstellen inkl. Bilder und Tags in die Datenbank
INSERT INTO haus (NAME, ADRESSE, LAND, BESCHREIBUNG, PERSONEN, PREIS, USER_ID) 
VALUES (?, ?, ?, ?, ?, ?, ?)


INSERT INTO img (HAUS_ID, img_type, img_url)
VALUES (?, Vorschaubild, ?);
INSERT INTO img (HAUS_ID, img_type, img_url)
VALUES (?, Aussenansicht, ?);
INSERT INTO img (HAUS_ID, img_type, img_url)
VALUES (?, Innenansicht, ?);
INSERT INTO img (HAUS_ID, img_type, img_url)
VALUES (?, Lageplan, ?);

INSERT INTO tags (tag_wert)
VALUES (?);

INSERT INTO tag_haus_relation (TAG_ID, HAUS_ID)
VALUES (?, ?);

-- Haus bearbeiten