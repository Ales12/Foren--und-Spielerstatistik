# Foren--und-Spielerstatistik
<br /> In der CSS muss die Gruppenfarben angepasst werden. Es ist immer group-gruppenid. Statistiken können nicht von User aktiviert oder deaktiviert werden. Ansicht kann auch nicht von deaktiviert werden.

<br /><br />
## benötigte Plugins
- Inplaytracker
- Wer wohnt wo?
- Joblist

## CSS
<br /><br />
**playeroverview.css**
```:root {
	/*allgemeine Chartsfarben*/
    --chart-color-1: #9b7aa8;
    --chart-color-2: #d98b9c;
    --chart-color-3: #7fa7a8;
    --chart-color-4: #e5b84c;
    --chart-color-5: #8c8cae;
    --chart-color-6: #c97878;

	/*Gruppenfarben - müssen angepasst werden*/
    --group-1: #9b7aa8;
    --group-2: #9674a5;
    --group-4: #d7859a;
    --group-8: #7fa8a8;

	/*Balkendiagramm Altersgruppen*/
	--chart-age-color: #b87991;
	
	/*Textfarbe*/
    --chart-text-color: #444;
}


/*grids*/
.po_grid_facts{
	display: grid;
	grid-template-columns: repeat(4, 25%);
	margin: 10px;
}

.po_grid_overall{
	display: grid;
	grid-template-columns: repeat(3, 33.333%);
	margin: 10px;
}

.po_grid_two{
		display: grid;
	grid-template-columns: repeat(2, 50%);
	margin: 10px;
}

/*forumoverview*/

.po_chart_age {
    position: relative;
    width: 100%;
    height: 300px;
    margin: 10px auto;
}

/*playeroverview*/
.po_box{
	padding: 10px;
	text-align: center;
	background: #efefef;
	box-sizing: border-box;
	font-size: 16px;
	margin: 5px;
}

.po_desc{
	font-size: 8px;
	text-transform: uppercase;
}

.po_playerbox{
	padding: 10px;
	background: #efefef;
	box-sizing: border-box;
	margin: 10px;
}

.po_player_grid{
		display: grid;
	grid-template-columns: repeat(3, 33.333%);
	margin: 10px ;
}

.po_playername{
	font-size: 14px;
	text-align: center;
	font-weight: bold;
}

.po_playerinfopoint{
	font-weight: bold;
	font-size: 11px;
	text-align: center;
	margin: 2px auto;
}

.po_playerinfo{
				font-size: 12px;
	text-align: center;
		margin: 2px auto;
}

.po_player_allcharaheadline{
					font-size: 12px;
	text-align: center;
		margin:5px auto;
	font-weight: bold;
}

.po_player_allchara{
	display: grid;
	grid-template-columns: 10% 90%;
	gap: 2px;
	align-items: center;
	margin: 0 1px 4px 1px;
}

.po_player_allcharaavatar img{
	width: 100%;	
}

.po_player_allcharaname{
	padding: 4px;	
}

/* Diagramme */
.po_chart {
    position: relative;
    width: fit-content;
    height: 180px;
    margin: auto;
}


/* Alle Kreisdiagramme gleich groß und gleich ausgerichtet */
.po_chart_pie {
    position: relative;
    width: 480px;
    height: 180px;
    margin: 10px auto;
}


/* Charaktere: eigener Kreis + eigene HTML-Legende */
.po_character_pie {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    width: 480px;
    height: 180px;
    margin: 10px auto;
}


/* Kreis beim Charakterdiagramm */
.po_character_pie canvas {
    flex: 0 0 180px !important;
    width: 180px !important;
    height: 180px !important;
    max-width: 180px !important;
    max-height: 180px !important;
}


/* Eigene Charakter-Legende */
.po_chart_legend {
    width: 170px;
    max-height: 130px;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 0 0 170px;
}


.po_chart_legend ul {
    list-style: none;
    padding: 0;
    margin: 0;
}


.po_chart_legend li {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 5px;
    font-size: 11px;
    color: var(--chart-text-color);
    cursor: pointer;
    line-height: 1.2;
}


.po_chart_legend li span {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    flex-shrink: 0;
}


/* Transparenter Hintergrund */
#charaPostsChart,
#genderChart,
#groupChart {
    background: transparent;
}

/*Charakterübersicht*/
.po_chara_grid{
	display: grid;
	grid-template-columns: repeat(3,33.333%);	
	margin-bottom: 10px;
}

.po_charabox{
	padding: 10px;
	background: #efefef;
	box-sizing: border-box;
	margin: 10px;
}

.po_charaname{
	font-size: 16px;
	margin: 10px auto;
	text-align: center;
}

.po_charaavatar{
	text-align: center;
margin: 10px auto;	
}

.po_charafactpoint{
	text-align: center;
	font-weight: bold;
	font-size: 13px;
}

.po_charafact{
		text-align: center;
	font-size: 11px;
}```
