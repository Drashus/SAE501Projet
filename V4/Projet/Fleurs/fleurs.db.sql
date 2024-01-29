BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "categorie" (
	"cat_code"	INTEGER,
	"cat_libelle"	VARCHAR(50) NOT NULL,
	PRIMARY KEY("cat_code" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "produit" (
	"pdt_ref"	char(3) NOT NULL,
	"pdt_designation"	VARCHAR(50) NOT NULL,
	"pdt_prix"	DECIMAL(5, 2) NOT NULL,
	"pdt_image"	VARCHAR(50) NOT NULL,
	"pdt_categorie"	INTEGER NOT NULL,
	CONSTRAINT "fk_idC" FOREIGN KEY("pdt_categorie") REFERENCES "categorie"("cat_code") ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY("pdt_ref")
);
CREATE TABLE IF NOT EXISTS "utilisateurs" (
	"pseudo"	VARCHAR NOT NULL,
	"pass"	VARCHAR NOT NULL,
	"statut"	VARCHAR NOT NULL DEFAULT 'VISITEUR',
	PRIMARY KEY("pseudo")
);
INSERT INTO "categorie" VALUES (1,'Bulbes');
INSERT INTO "categorie" VALUES (2,'Plantes à massif');
INSERT INTO "categorie" VALUES (3,'Rosiers');
INSERT INTO "produit" VALUES ('b01','3 bulbes de bégonias',5,'bulbes_begonia',1);
INSERT INTO "produit" VALUES ('b02','10 bulbes de dahlias',12,'bulbes_dahlia',1);
INSERT INTO "produit" VALUES ('b03','50 glaïeuls',9,'bulbes_glaieul',1);
INSERT INTO "produit" VALUES ('m01','Lot de 3 marguerites',5,'massif_marguerite',2);
INSERT INTO "produit" VALUES ('m02','Pour un bouquet de 6 pensées',6,'massif_pensee',2);
INSERT INTO "produit" VALUES ('m03','Mélange varié de 10 plantes à massif',15,'massif_melange',2);
INSERT INTO "produit" VALUES ('r01','1 pied spécial grandes fleurs',20,'rosiers_gdefleur',3);
INSERT INTO "produit" VALUES ('r02','Une variété sélectionnée pour son parfum',9,'rosiers_parfum',3);
INSERT INTO "produit" VALUES ('r03','Rosier arbuste',8,'rosiers_arbuste',3);
INSERT INTO "utilisateurs" VALUES ('admin','admin','administrateur');
INSERT INTO "utilisateurs" VALUES ('toto','lannion','employés');
INSERT INTO "utilisateurs" VALUES ('visiteur','lannion','visiteur');
INSERT INTO "utilisateurs" VALUES ('admin@admin.fr','admin','administrateur');
COMMIT;
