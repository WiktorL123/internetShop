-- Created by Vertabelo (http://vertabelo.com)
-- Last modification date: 2023-06-20 17:24:12.086
create database sklep;
use sklep
-- tables
-- Table: adresy
CREATE TABLE adresy (
    id_adresu int  AUTO_INCREMENT,
    miasto varchar(64)  NOT NULL,
    kod_pocztowy varchar(64)  NOT NULL,
    ulica varchar(64)  NOT NULL,
    numer_budynku varchar(64)  NOT NULL,
    numer_mieszkania int  NOT NULL,
    id_uzytkownika int  NOT NULL,
    CONSTRAINT adresy_pk PRIMARY KEY (id_adresu)
);

-- Table: assoc_zamowienia_produkty
CREATE TABLE assoc_zamowienia_produkty (
    produkty_id_produktu int  NOT NULL,
    zamowienia_id_zamowienia int  NOT NULL,
    ilosc int  NOT NULL,
    CONSTRAINT assoc_zamowienia_produkty_pk PRIMARY KEY (produkty_id_produktu,zamowienia_id_zamowienia)
);

-- Table: dostawcy
CREATE TABLE dostawcy (
    id_dostawcy int  NOT NULL,
    nazwa varchar(64)  NOT NULL,
    CONSTRAINT dostawcy_pk PRIMARY KEY (id_dostawcy)
);

-- Table: dostawy
CREATE TABLE dostawy (
    id_dostawy int  NOT NULL,
    adresy_id_adresu int  NOT NULL,
    dostawcy_id_dostawcy int  NOT NULL,
    CONSTRAINT dostawy_pk PRIMARY KEY (id_dostawy)
);

-- Table: kategoria
CREATE TABLE kategoria (
    id_kategori int  auto_increment,
    nazwa int  NOT NULL,
    CONSTRAINT kategoria_pk PRIMARY KEY (id_kategori)
);

-- Table: oceny
CREATE TABLE oceny (
    id_oceny int  auto_increment,
    ocena double(2,2)  NOT NULL,
    produkty_id_produktu int  NOT NULL,
    uzytkownicy_id int,
    CONSTRAINT oceny_pk PRIMARY KEY (id_oceny)
);

-- Table: opinie
CREATE TABLE opinie (
    id_opini int  auto_increment,
    produkty_id_produktu int  NOT NULL,
    tresc varchar(255)  NOT NULL,
    uzytkownicy_id int,
    CONSTRAINT opinie_pk PRIMARY KEY (id_opini)
);

-- Table: platnosci
CREATE TABLE platnosci (
    id_platnosci int  NOT NULL,
    typ int  NOT NULL,
    CONSTRAINT platnosci_pk PRIMARY KEY (id_platnosci)
);

-- Table: produkty
CREATE TABLE produkty (
    id_produktu int  auto_increment,
    nazwa varchar(64)  NOT NULL,
    opis varchar(64)  NOT NULL,
    cena decimal(64,2)  NOT NULL,
    ilosc int  NOT NULL,
    id_kategori int  NOT NULL,
    zdjecie blob  NOT NULL,
    CONSTRAINT produkty_pk PRIMARY KEY (id_produktu)
);

-- Table: uzytkownicy
CREATE TABLE uzytkownicy (
    id int  auto_increment,
    user_name varchar(64)  NOT NULL,
    password varchar(64)  NOT NULL,
    adres_email varchar(64)  NOT NULL,
    imie varchar(64)  NOT NULL,
    nazwisko varchar(64)  NOT NULL,
    CONSTRAINT uzytkownicy_pk PRIMARY KEY (id)
);

-- Table: zamowienia
CREATE TABLE zamowienia (
    id_zamowienia int  NOT NULL,
    id_dostawy int  NOT NULL,
    id_uzytkownika int  NOT NULL,
    platnosci_id_platnosci int  NOT NULL,
    data date  NOT NULL,
    CONSTRAINT zamowienia_pk PRIMARY KEY (id_zamowienia)
);

-- foreign keys
-- Reference: adresy_uzytkownicy (table: adresy)
ALTER TABLE adresy ADD CONSTRAINT adresy_uzytkownicy FOREIGN KEY adresy_uzytkownicy (id_uzytkownika)
    REFERENCES uzytkownicy (id);

-- Reference: assoc_zamowienia_produkty_produkty (table: assoc_zamowienia_produkty)
ALTER TABLE assoc_zamowienia_produkty ADD CONSTRAINT assoc_zamowienia_produkty_produkty FOREIGN KEY assoc_zamowienia_produkty_produkty (produkty_id_produktu)
    REFERENCES produkty (id_produktu);

-- Reference: assoc_zamowienia_produkty_zamowienia (table: assoc_zamowienia_produkty)
ALTER TABLE assoc_zamowienia_produkty ADD CONSTRAINT assoc_zamowienia_produkty_zamowienia FOREIGN KEY assoc_zamowienia_produkty_zamowienia (zamowienia_id_zamowienia)
    REFERENCES zamowienia (id_zamowienia);

-- Reference: dostawy_adresy (table: dostawy)
ALTER TABLE dostawy ADD CONSTRAINT dostawy_adresy FOREIGN KEY dostawy_adresy (adresy_id_adresu)
    REFERENCES adresy (id_adresu);

-- Reference: dostawy_dostawcy (table: dostawy)
ALTER TABLE dostawy ADD CONSTRAINT dostawy_dostawcy FOREIGN KEY dostawy_dostawcy (dostawcy_id_dostawcy)
    REFERENCES dostawcy (id_dostawcy);

-- Reference: oceny_produkty (table: oceny)
ALTER TABLE oceny ADD CONSTRAINT oceny_produkty FOREIGN KEY oceny_produkty (produkty_id_produktu)
    REFERENCES produkty (id_produktu);

-- Reference: opinie_produkty (table: opinie)
ALTER TABLE opinie ADD CONSTRAINT opinie_produkty FOREIGN KEY opinie_produkty (produkty_id_produktu)
    REFERENCES produkty (id_produktu);

-- Reference: produkty_kategoria (table: produkty)
ALTER TABLE produkty ADD CONSTRAINT produkty_kategoria FOREIGN KEY produkty_kategoria (id_kategori)
    REFERENCES kategoria (id_kategori);

-- Reference: zamowienia_dostawy (table: zamowienia)
ALTER TABLE zamowienia ADD CONSTRAINT zamowienia_dostawy FOREIGN KEY zamowienia_dostawy (id_dostawy)
    REFERENCES dostawy (id_dostawy);

-- Reference: zamowienia_platnosci (table: zamowienia)
ALTER TABLE zamowienia ADD CONSTRAINT zamowienia_platnosci FOREIGN KEY zamowienia_platnosci (platnosci_id_platnosci)
    REFERENCES platnosci (id_platnosci);

-- Reference: zamowienia_uzytkownicy (table: zamowienia)
ALTER TABLE zamowienia ADD CONSTRAINT zamowienia_uzytkownicy FOREIGN KEY zamowienia_uzytkownicy (id_uzytkownika)
    REFERENCES uzytkownicy (id);
alter table opinie add constraint opinie_uzytkownicy foreign key opinie_uzytkownicy (uzytkownicy_id) references uzytkownicy(id);
alter table oceny add constraint oceny_uzytkownicy foreign key oceny_uzytkownicy (uzytkownicy_id) references  uzytkownicy(id);

-- End of file.

