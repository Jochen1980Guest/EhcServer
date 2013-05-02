<?php

/*

=== Notizen zum Projekt ========================================================

Version 1.0 [01.05.2013]

Localhost-Url: http://zendskeletonapplication.localhost/

=== === Ticket Notes === ===

Github: 
https://github.com/Jochen1980/ZendSkeletonApplication/issues?page=1&state=open

Ticket #2: Layout anpassen
Ziel soll die Skeleton-App-Struktur sein;

=== === TODOs === ===
Start, Unternehmenshistorie, das Porici muss raus;
U-Historie umstellen, im Jahr 2008 ... umstellen;
Alfred Lodes in Slideshow als ersten;
Frau Lodes hat die Durchwahl 40 statt 0,, Kummer 42;
Blogsystem, samt Aktuelles Tab;

=== === Skeleton-Anpassung === ===
Check der Unterseiten, diese dann als Actions im IndexController der Application
unterbringen;
Views anlegen also die phtml-Dateien;
Layout anpassen, die Action-Aufrufe testen, Routes anlegen;
Website-Titel aendern und dabei lokalisieren;
Datenbank anlegen und Widgets fertig machen;


=== === Github und EGit === ===
Repository ist erreichbar unter 
https://github.com/Jochen1980/ZendSkeletonApplication
Commit erfolgt lokal durch commit, auf den Server via push;

=== === Datenbank === ===

Zugang: zf2test mit zf2test auf localhost.zf2test;

Tabelle widget mit id (autogen), key, value_de;
UnitTest fuer Db anlegen;



*/

/*
 Stylesheet fuer SKP, 01.05.2013

960-gs | Bootstrap
--------------------------------

Website-Struktur

960gs - 12 Spalten pro Zeile
- body-Element hat weissen Hintergrund und eine min-width von 960px;
- Container_12 fasst die Website bei 960gs;
- Gesamtbreite fuer div.grid_12 betraegt 940px;
- Navigation wird mittels suckerfish realisiert;

Bootstrap -  12 Spalten pro Zeile
- benoetigt HTML-5, siehe http://twitter.github.io/bootstrap/scaffolding.html
- div.container hat die width 1170px auf dem Desktoprechner und forciert ein fixes Layout;
- div.container fasst div.row, diese wiederum div.spanX Elemente;
- Spezialklassen sind div.hero-unit;

Anpassungen:
- Headbereich Titel anpassen;
- Headbereich anpassen, Favivon;
- Transfer von Navigation

*/


?>