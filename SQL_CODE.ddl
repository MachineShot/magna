#@(#) script.ddl

DROP TABLE IF EXISTS Saskaita;
DROP TABLE IF EXISTS Komentaras;
DROP TABLE IF EXISTS Uzsakymas;
DROP TABLE IF EXISTS Internetine_reklama;
DROP TABLE IF EXISTS Fizine_reklama;
DROP TABLE IF EXISTS Reklama;
DROP TABLE IF EXISTS Idarbina;
DROP TABLE IF EXISTS Tiekejas;
DROP TABLE IF EXISTS Mokejimo_duomenys;
DROP TABLE IF EXISTS Agentura;
DROP TABLE IF EXISTS Naudotojas;
DROP TABLE IF EXISTS El_laisko_sablonas;
CREATE TABLE El_laisko_sablonas
(
	id int NOT NULL,
	zinute varchar (255) NOT NULL,
	paskirtis ENUM('registracijos_patvirtinimas', 'slaptazodzio_keitimas', 'saskaitos_sukurimas') NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE Naudotojas
(
	slapyvardis varchar (255) NOT NULL,
	email varchar (255) NOT NULL,
	vardas varchar (255) NOT NULL,
	pavarde varchar (255) NOT NULL,
	tel_nr varchar (255) NOT NULL,
	slaptazodis varchar (255) NOT NULL,
	gimimo_data date NOT NULL,
	tipas ENUM('tiekejas', 'uzsakovas', 'vadovas') NOT NULL,
	PRIMARY KEY(slapyvardis)
);

CREATE TABLE Agentura
(
	id int NOT NULL AUTO_INCREMENT,
	pavadinimas varchar (255) NOT NULL,
	sukurimo_data date NOT NULL,
	adresas varchar (255) NOT NULL,
	aprasymas varchar (255) NOT NULL,
	imones_kodas varchar (255) NOT NULL,
	miestas varchar (255) NOT NULL,
	pasto_kodas varchar (255) NOT NULL,
	fk_vadovo_slapyvardis varchar (255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(fk_vadovo_slapyvardis),
	CONSTRAINT valdo FOREIGN KEY(fk_vadovo_slapyvardis) REFERENCES Naudotojas (slapyvardis)
);

CREATE TABLE Mokejimo_duomenys
(
	id int NOT NULL AUTO_INCREMENT,
	korteles_nr varchar (255) NOT NULL,
	cvv int NOT NULL,
	galiojimo_data datetime NOT NULL,
	vardas varchar (255) NOT NULL,
	pavarde varchar (255) NOT NULL,
	adresas varchar (255) NOT NULL,
	miestas varchar (255) NOT NULL,
	pasto_kodas varchar (255) NOT NULL,
	fk_naudotojo_slapyvardis varchar (255) NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT apmoka_su FOREIGN KEY(fk_naudotojo_slapyvardis) REFERENCES Naudotojas (slapyvardis)
);

CREATE TABLE Tiekejas
(
	id int NOT NULL AUTO_INCREMENT,
	isidarbinimo_data date NOT NULL,
	adresas varchar (255) NOT NULL,
	darbo_stazas float NOT NULL,
	fk_naudotojo_slapyvardis varchar (255) NOT NULL,
	PRIMARY KEY(id),
	UNIQUE(fk_naudotojo_slapyvardis),
	CONSTRAINT yra FOREIGN KEY(fk_naudotojo_slapyvardis) REFERENCES Naudotojas (slapyvardis)
);

CREATE TABLE Idarbina
(
	fk_agentura_id int NOT NULL,
	fk_tiekejas_id int NOT NULL,
	PRIMARY KEY(fk_tiekejas_id, fk_agentura_id),
	CONSTRAINT idarbina FOREIGN KEY(fk_tiekejas_id) REFERENCES Tiekejas (id)
);

CREATE TABLE Reklama
(
	id int NOT NULL AUTO_INCREMENT,
	kaina decimal(18, 2) NOT NULL,
	pavadinimas varchar (255) NOT NULL,
	sudarymo_data datetime NOT NULL,
	galiojimo_laikotarpis datetime NOT NULL,
	aktyvi boolean NOT NULL,
	fk_tiekejo_id int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT daro FOREIGN KEY(fk_tiekejo_id) REFERENCES Tiekejas (id)
);

CREATE TABLE Fizine_reklama
(
	id int NOT NULL AUTO_INCREMENT,
	miestas varchar (255) NOT NULL,
	adresas varchar (255) NOT NULL,
	koordinates varchar (255) NOT NULL,
	dydis varchar (255) NOT NULL,
	fk_reklamos_id int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT turi_fizine_reklama FOREIGN KEY(fk_reklamos_id) REFERENCES Reklama (id)
);

CREATE TABLE Internetine_reklama
(
	id int NOT NULL AUTO_INCREMENT,
	puslapio_adresas varchar (255) NOT NULL,
	tipas ENUM('animuota', 'statine') NOT NULL,
	fk_reklamos_id int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT turi_internetine_reklama FOREIGN KEY(fk_reklamos_id) REFERENCES Reklama (id)
);

CREATE TABLE Uzsakymas
(
	nr int NOT NULL,
	kaina decimal(18, 2) NOT NULL,
	sudarymo_data datetime NOT NULL,
	pabaigos_data datetime NOT NULL,
	busena ENUM('ruosiama', 'vykdoma', 'neaktyvi') NOT NULL,
	fk_uzsakovo_slapyvardis varchar (255) NOT NULL,
	fk_reklama_id int NOT NULL,
	PRIMARY KEY(nr),
	CONSTRAINT uzsako FOREIGN KEY(fk_uzsakovo_slapyvardis) REFERENCES Naudotojas (slapyvardis),
	CONSTRAINT priklauso FOREIGN KEY(fk_reklama_id) REFERENCES Reklama (id)
);

CREATE TABLE Komentaras
(
	id int NOT NULL AUTO_INCREMENT,
	zinute varchar (255) NOT NULL,
	issiuntimo_data datetime NOT NULL,
	tipas ENUM('atsiliepimas', 'prasymas') NOT NULL,
	fk_uzsakymo_nr int NOT NULL,
	PRIMARY KEY(id),
	CONSTRAINT parasomas FOREIGN KEY(fk_uzsakymo_nr) REFERENCES Uzsakymas (nr)
);

CREATE TABLE Saskaita
(
	nr int NOT NULL,
	apmokejimo_data datetime NOT NULL,
	suma decimal(18, 2) NOT NULL,
	fk_uzsakymo_nr int NOT NULL,
	PRIMARY KEY(nr),
	CONSTRAINT ieina FOREIGN KEY(fk_uzsakymo_nr) REFERENCES Uzsakymas (nr)
);
