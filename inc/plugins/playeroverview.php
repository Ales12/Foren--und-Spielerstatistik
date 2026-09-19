<?php

// Disallow direct access to this file for security reasons
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.");
}


function playeroverview_info()
{
    return array(
        "name"            => "Foren- und Spielerstatistik",
        "description"    => "Eine Foren- und Spielerstatistik. Diagramme wurden mit der Open Sorce chart.js erstellt.",
        "website"        => "https://github.com/Ales12/Foren--und-Spielerstatistik",
        "author"        => "Ales",
        "authorsite"    => "https://github.com/Ales12",
        "version"        => "1.0",
        "guid"             => "",
        "codename"        => "",
        "compatibility" => "*"
    );
}

function playeroverview_install()
{
    global $db, $mybb;

    $setting_group = array(
        'name' => 'playeroverview',
        'title' => 'Einstellungen für Foren- und Spielerstatistik',
        'description' => 'Hier kannst du alles zur Foren- und Spielerstatistik einstellen.',
        'disporder' => 5, // The order your setting group will display
        'isdefault' => 0
    );

    $gid = $db->insert_query("settinggroups", $setting_group);

    $setting_array = array(
        // A text setting
        'playeroverview_forumbirthday' => array(
            'title' => 'Forengeburtstag',
            'description' => 'Wann hat das Forum Geburtstag? Bitte gebe es so an <b>YYYY-MM-DD</b>:',
            'optionscode' => 'text',
            'value' => '2026-01-01', // Default
            'disporder' => 1
        ),
        // A select box
        'playeroverview_playername' => array(
            'title' => 'Profilfeld für Spielername',
            'description' => 'Gib hier an, welche FID das Profilfeld hat, in welches der Username gespeichert wird:',
            'optionscode' => "text",
            'value' => 'fid1',
            'disporder' => 2
        ),
        'playeroverview_postfrequence' => array(
            'title' => 'Profilfeld für Postfrequenz',
            'description' => 'Gib hier an, welche FID das Profilfeld hat, in welches die Postfrequenz gespeichert wird:',
            'optionscode' => "text",
            'value' => 'fid6',
            'disporder' => 3
        ),
        'playeroverview_avaragecharacters' => array(
            'title' => 'Profilfeld für Zeichenlänge',
            'description' => 'Gib hier an, welche FID das Profilfeld hat, in welches Zeichenlänge gespeichert wird:',
            'optionscode' => "text",
            'value' => 'fid7',
            'disporder' => 4
        ),
        'playeroverview_gender' => array(
            'title' => 'Profilfeld für Charaktergeschlecht',
            'description' => 'Gib hier an, welche FID das Profilfeld hat, in welches das Geschlecht gespeichert wird:',
            'optionscode' => "text",
            'value' => 'fid3',
            'disporder' => 5
        ),
        'playeroverview_groups' => array(
            'title' => 'Profilfeld für Charaktergeschlecht',
            'description' => 'Gib hier an, welche FID das Profilfeld hat, in welches das Geschlecht gespeichert wird:',
            'optionscode' => "groupselect",
            'value' => 0,
            'disporder' => 5
        ),
        'playeroverview_notcountaccounts' => array(
            'title' => 'Ausgegrenzte Account',
            'description' => 'Gib an, welche Accounts nicht beachtet werden sollen:',
            'optionscode' => "text",
            'value' => '1,2',
            'disporder' => 7
        ),
        'playeroverview_inplaydate' => array(
            'title' => 'Inplayzeitraum',
            'description' => 'Gebe hier das Datum des <b>letzten Inplaytags</b> ein. <b>Actung!</b> Muss bei jedem Zeitsprung angepasst werden.',
            'optionscode' => "text",
            'value' => '01.01.2024',
            'disporder' => 8
        ),
    );

    foreach ($setting_array as $name => $setting) {
        $setting['name'] = $name;
        $setting['gid'] = $gid;

        $db->insert_query('settings', $setting);
    }
    // Don't forget this!
    rebuild_settings();

    // templates

    $templategroup = array(
        "prefix" => "playeroverview",
        "title" => $db->escape_string("Foren- und Spielerstatistik"),
    );
    $db->insert_query("templategroups", $templategroup);


    $insert_array = array(
        'title' => 'playeroverview_header',
        'template' => $db->escape_string('<li><a href="{$mybb->settings[\'bburl\']}/misc.php?action=forumoverview" class="search">{$lang->forumoverview_header}</a></li>
<li><a href="{$mybb->settings[\'bburl\']}/misc.php?action=playeroverview" class="search">{$lang->playeroverview_header}</a></li>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);


    $insert_array = array(
        'title' => 'playeroverview_forenstatistic',
        'template' => $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->forumoverview}</title>
{$headerinclude}
</head>
<body>
{$header}
<table class="tborder" border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}">
	<tr><td class="thead"><strong>{$lang->forumoverview} </strong></td></tr>
<tr>
	<td class="trow1"><div class="po_grid_overall">
		<div class="po_box">	<div class="po_desc">{$lang->forumoverview_birthday}</div>
			{$opendate}
		
		</div>
				<div class="po_box"><div class="po_desc">{$lang->forumoverview_date_desc}</div>
			{$fulldate}
			
		</div>
						<div class="po_box"><div class="po_desc">{$lang->forumoverview_date_desc}</div>
			{$days}
			
		</div>
		</div>	
		<div class="po_grid_overall">
			<div class="po_box">	<div class="po_desc">{$lang->playeroverview_playercount}</div>
				{$countplayer}
		
		</div>
	
				<div class="po_box">	<div class="po_desc">{$lang->playeroverview_characount}</div>
		{$countcharas}
		
		</div>	
						<div class="po_box">	<div class="po_desc">{$lang->playeroverview_avaragcharas}</div>
			{$avaragecharas}
		
		</div>	
					
		</div>
	<div class="po_grid_facts">
				<div class="po_box"><div class="po_desc">{$lang->forumoverview_inplayscenes}</div>
			{$count_threads}
			
		</div>
				<div class="po_box">	<div class="po_desc">{$lang->forumoverview_inplayposts}</div>
			{$count_posts}
		
		</div>
						<div class="po_box">	<div class="po_desc">{$lang->forumoverview_avaragewords}</div>
			{$words}
		
		</div>
								<div class="po_box"><div class="po_desc">{$lang->forumoverview_avaragcharacters}</div>
			{$characters}
		</div>
		</div>
		<h1>{$lang->forumoverview_characterdistribution}</h1>
		<div class="po_grid_two"><div>
<strong>{$lang->playeroverview_charaprogender}</strong>
<div class="po_chart po_chart_pie">
    <canvas id="genderChart"></canvas>
</div>
			</div>
			<div>
<strong>{$lang->playeroverview_charaprogroup}</strong>
<div class="po_chart po_chart_pie">
    <canvas id="groupChart"></canvas>
				</div></div>
		</div>
</div>
	<strong>Altersverteilung der Charaktere</strong>

<div class="po_chart po_chart_age">
    <canvas id="ageChart"></canvas>
</div>
	</td>
	</tr>
	</table>
{$footer}{$po_js}
</body>
</html>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_javascript',
        'template' => $db->escape_string('<script>

/*
 * =========================================================
 * Gemeinsame Chart-Einstellungen
 * =========================================================
 */

const chartColors = [

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-1\')
        .trim(),

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-2\')
        .trim(),

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-3\')
        .trim(),

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-4\')
        .trim(),

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-5\')
        .trim(),

    getComputedStyle(document.documentElement)
        .getPropertyValue(\'--chart-color-6\')
        .trim()
];


const chartTextColor = getComputedStyle(document.documentElement)
    .getPropertyValue(\'--chart-text-color\')
    .trim();


/*
 * =========================================================
 * Werte über Balken
 * =========================================================
 */

const valueLabels = {

    id: \'valueLabels\',

    afterDatasetsDraw(chart) {

        const { ctx } = chart;

        ctx.save();

        chart.data.datasets.forEach((dataset, datasetIndex) => {

            const meta = chart.getDatasetMeta(datasetIndex);

            meta.data.forEach((bar, index) => {

                const value = dataset.data[index];

                ctx.fillStyle = chartTextColor;

                ctx.font = \'11px Arial\';

                ctx.textAlign = \'center\';

                ctx.textBaseline = \'bottom\';

                ctx.fillText(
                    value,
                    bar.x,
                    bar.y - 5
                );

            });

        });

        ctx.restore();
    }
};


/*
 * =========================================================
 * HTML-Legende für Charaktere
 * =========================================================
 */

const htmlLegendPlugin = {

    id: \'htmlLegend\',

    afterUpdate(chart, args, options) {

        const container = document.getElementById(
            options.containerID
        );

        if (!container) {
            return;
        }


        let list = container.querySelector(\'ul\');


        if (!list) {

            list = document.createElement(\'ul\');

            container.appendChild(list);
        }


        /*
         * Alte Einträge entfernen
         */
        while (list.firstChild) {
            list.firstChild.remove();
        }


        /*
         * Legenden-Einträge holen
         */
        const items =
            chart.options.plugins.legend.labels.generateLabels(chart);


        items.forEach(item => {

            const li = document.createElement(\'li\');


            /*
             * Klick auf Charakter
             */
            li.onclick = () => {

                chart.toggleDataVisibility(item.index);

                chart.update();
            };


            /*
             * Farbfeld
             */
            const boxSpan = document.createElement(\'span\');

            boxSpan.style.backgroundColor = item.fillStyle;

            li.appendChild(boxSpan);


            /*
             * Charaktername
             */
            const text = document.createTextNode(item.text);

            li.appendChild(text);


            list.appendChild(li);

        });

    }

};


/*
 * =========================================================
 * SZENEN PRO CHARAKTER
 * Nur Playeroverview
 * =========================================================
 */

const charaThreadsCanvas =
    document.getElementById(\'charaThreadsChart\');


if (charaThreadsCanvas && {$chara_labels}.length > 0) {

    new Chart(charaThreadsCanvas, {

        type: \'bar\',

        plugins: [valueLabels],

        data: {

            labels: {$chara_labels}.map(
                name => name.split(\' \')
            ),

            datasets: [{

                data: {$chara_threads},

                backgroundColor: chartColors,

                borderWidth: 0,

                barThickness: 18

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            plugins: {

                legend: {
                    display: false
                },

                tooltip: {
                    enabled: true
                }

            },


            layout: {

                padding: {

                    top: 20,
                    left: 5,
                    right: 5,
                    bottom: 0

                }

            },


            scales: {

                x: {

                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    },

                    ticks: {

                        color: chartTextColor,

                        font: {
                            size: 11
                        },

                        minRotation: 45,

                        maxRotation: 45,

                        padding: 4

                    }

                },


                y: {

                    display: false,

                    beginAtZero: true,

                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    }

                }

            }

        }

    });

}


/*
 * =========================================================
 * POSTS PRO CHARAKTER
 * Nur Playeroverview
 * =========================================================
 */

const charaPostsCanvas =
    document.getElementById(\'charaPostsChart\');


if (charaPostsCanvas && {$chara_post_labels}.length > 0) {

    new Chart(charaPostsCanvas, {

        type: \'pie\',

        plugins: [htmlLegendPlugin],

        data: {

            labels: {$chara_post_labels},

            datasets: [{

                data: {$chara_posts},

                backgroundColor: chartColors,

                borderWidth: 0

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            plugins: {

                /*
                 * Chart.js Legende ausschalten
                 */
                legend: {
                    display: false
                },


                /*
                 * Eigene HTML-Legende
                 */
                htmlLegend: {

                    containerID:
                        \'charaPostsLegend\'

                },


                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return \' \' +
                                context.label +
                                \': \' +
                                context.raw +
                                \' Posts\';

                        }

                    }

                }

            }

        }

    });

}


/*
 * =========================================================
 * GESCHLECHT
 * Playeroverview + Forenoverview
 * =========================================================
 */

const genderCanvas =
    document.getElementById(\'genderChart\');


if (genderCanvas && {$gender_labels}.length > 0) {

    new Chart(genderCanvas, {

        type: \'pie\',

        data: {

            labels: {$gender_labels},

            datasets: [{

                data: {$gender_values},

                backgroundColor: chartColors,

                borderWidth: 0

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            plugins: {

                legend: {

                    display: true,

                    position: \'right\',

                    labels: {

                        color: chartTextColor,

                        font: {
                            size: 11
                        },

                        boxWidth: 12,

                        padding: 8

                    }

                },


                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return \' \' +
                                context.label +
                                \': \' +
                                context.raw;

                        }

                    }

                }

            }

        }

    });

}


/*
 * =========================================================
 * GRUPPEN
 * Playeroverview + Forenoverview
 * =========================================================
 */

const groupColors = {$group_colors}.map(function(groupId) {

    const color = getComputedStyle(
        document.documentElement
    )
        .getPropertyValue(\'--group-\' + groupId)
        .trim();


    return color || chartColors[
        {$group_colors}.indexOf(groupId)
        % chartColors.length
    ];

});


const groupCanvas =
    document.getElementById(\'groupChart\');


if (groupCanvas && {$group_labels}.length > 0) {

    new Chart(groupCanvas, {

        type: \'pie\',

        data: {

            labels: {$group_labels},

            datasets: [{

                data: {$group_values},

                backgroundColor: groupColors,

                borderWidth: 0

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            plugins: {

                legend: {

                    display: true,

                    position: \'right\',

                    labels: {

                        color: chartTextColor,

                        font: {
                            size: 11
                        },

                        boxWidth: 12,

                        padding: 8

                    }

                },


                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return \' \' +
                                context.label +
                                \': \' +
                                context.raw;

                        }

                    }

                }

            }

        }

    });

}


/*
 * =========================================================
 * ALTER
 * Nur Forenoverview
 * =========================================================
 */

const ageCanvas =
    document.getElementById(\'ageChart\');


if (ageCanvas && {$age_labels}.length > 0) {

    const ageChartColor = getComputedStyle(
        document.documentElement
    )
        .getPropertyValue(\'--chart-age-color\')
        .trim();


    new Chart(ageCanvas, {

        type: \'bar\',

        plugins: [valueLabels],

        data: {

            labels: {$age_labels},

            datasets: [{

                data: {$age_values},

                backgroundColor: ageChartColor,

                borderWidth: 0,

                barThickness: 18

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            plugins: {

                legend: {
                    display: false
                },


                tooltip: {

                    callbacks: {

                        label: function(context) {

                            return \' \' +
                                context.raw +
                                \' Charaktere\';

                        }

                    }

                }

            },


            layout: {

                padding: {

                    top: 20,
                    left: 5,
                    right: 5,
                    bottom: 5

                }

            },


            scales: {

                x: {

                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    },

                    ticks: {

                        color: chartTextColor,

                        font: {
                            size: 11
                        },

                        padding: 4

                    },

                    title: {

                        display: true,

                        text: \'Alter (Jahre)\',

                        color: chartTextColor,

                        font: {
                            size: 11
                        }

                    }

                },


                y: {

                    display: false,

                    beginAtZero: true,

                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    }

                }

            }

        }

    });

}

</script>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_playeroverview',
        'template' => $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->playeroverview}</title>
{$headerinclude}
</head>
<body>
{$header}
<table class="tborder" border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}">
	<tr><td class="thead"><strong>{$lang->playeroverview} </strong></td></tr>
<tr>
	<td class="trow1">
		<strong>{$lang->playeroveriew_overall}</strong>
		<div class="po_grid_overall">
			<div class="po_box">	<div class="po_desc">{$lang->playeroverview_playercount}</div>
				{$countplayer}
		
		</div>
	
				<div class="po_box">			<div class="po_desc">{$lang->playeroverview_characount}</div>
		{$countcharas}

		</div>	
						<div class="po_box">		<div class="po_desc">{$lang->playeroverview_avaragcharas}</div>
			{$avaragecharas}
	
		</div>	
						<div class="po_box"><div class="po_desc">{$lang->forumoverview_inplayscenes}</div>
				{$count_threads}
			
		</div>
	
				<div class="po_box">			<div class="po_desc">{$lang->forumoverview_inplayposts}</div>
		{$count_posts}

		</div>	
						<div class="po_box">			<div class="po_desc">{$lang->playeroverview_avaragescene}</div>
			{$avaragescene}

		</div>	
		</div>
		
		<strong>{$lang->playeroverview_overview}</strong>
		<div class="po_player_grid">
			{$player}
		</div>
	</td>
	</tr>
	</table>
{$footer}
</body>
</html>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_playeroverview_player',
        'template' => $db->escape_string('<div class="po_playerbox">
	<div class="po_playername">{$playername}</div>
	<div class="po_playerinfopoint">{$lang->playeroverview_regdate}</div>
	<div class="po_playerinfo">{$regdate}</div>
		<div class="po_playerinfopoint">{$lang->playeroverview_lastseen}</div>
	<div class="po_playerinfo">{$lastseen}</div>
			<div class="po_playerinfopoint">{$lang->playeroverview_inplaystat}</div>
	<div class="po_playerinfo">{$playerinplay}</div>
	<div class="po_playerinfo">{$playerstat}</div>
	<div class="po_player_allcharaheadline">{$charaheadline}</div>
	{$charas}
</div>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_playeroverview_player_charas',
        'template' => $db->escape_string('<div class="po_player_allchara">
	<div class="po_player_allcharaavatar">
		<img src="{$avatar}">
	</div>
	<div class="po_player_allcharaname">
		{$charaname}
		<div class="smalltext">{$charalastseen}</div>
	</div>
</div>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_playerstat',
        'template' => $db->escape_string('<html>
<head>
    <title>{$mybb->settings[\'bbname\']} - {$showplayeroverview}</title>
    {$headerinclude}
</head>
<body>
{$header}
<table class="tborder" border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}">

    <tr>
        <td class="thead">
            <strong>{$showplayeroverview}</strong>
        </td>
    </tr>

    <tr>
        <td class="trow1">

            <h1>{$lang->playeroveriew_overall}</h1>

            <div class="po_grid_facts">

                <div class="po_box"><div class="po_desc">{$lang->playeroverview_playerstat_regdate}</div>
                    {$user_regdate}
                    
                </div>

                <div class="po_box">           <div class="po_desc">{$lang->playeroverview_playerstat_lastseen}</div>
                    {$lastactive}
         
                </div>

                <div class="po_box"> <div class="po_desc">{$lang->playeroverview_postfrequence}</div>
                    {$postfrequence}
                   
                </div>

                <div class="po_box"><div class="po_desc">{$lang->playeroverview_avaragecharacters}</div>
                    {$postcharacters}
                    
                </div>

                <div class="po_box"> <div class="po_desc">{$lang->playeroverview_characount}</div>
                    {$characount}
                   
                </div>

                <div class="po_box"> <div class="po_desc">{$lang->forumoverview_inplayscenes}</div>
                    {$threadcount}
                   
                </div>

                <div class="po_box">  <div class="po_desc">{$lang->forumoverview_inplayposts}</div>
                    {$postcount}
                  
                </div>

                <div class="po_box">    <div class="po_desc">{$lang->playeroverview_avaragescene}</div>
                    {$avaragescene}
                
                </div>

                <div class="po_box"> <div class="po_desc">{$lang->playeroverview_countwords}</div>
                    {$wordcount}
                   
                </div>
  
                <div class="po_box">  <div class="po_desc">{$lang->playeroverview_countcharacters}</div>
                    {$charactercount}
                
                </div>

                <div class="po_box">  <div class="po_desc">{$lang->playeroverview_wordspropost}</div>
                    {$averagewords}
                  
                </div>

                <div class="po_box">    <div class="po_desc">{$lang->playeroverview_lastpost}</div>
                    <a href="{$lastpost_link}">{$lastpost_subject}</a>
                    <div class="smalltext">{$lastpost_date}</div>
                
                </div>

            </div>


            <h1>{$lang->playeroverview_overview}</h1>
			
			           <div class="po_grid_overall">
						                   <div class="po_box">           <div class="po_desc">{$lang->playeroverview_characount}</div>
                    {$characount}
         
                </div>

						        <div class="po_box"> <div class="po_desc">{$lang->playeroverview_firstchara}</div>
                    {$first_character}
                   
                </div>

                <div class="po_box"> <div class="po_desc">{$lang->playeroverview_lastchara}</div>
                    {$newest_character}
                   
                </div>

                <div class="po_box">    <div class="po_desc">{$lang->playeroverview_youngestchara}</div>
                          {$youngest_character} ({$youngest_age})
                
                </div>
					                <div class="po_box"> <div class="po_desc">{$lang->playeroverview_oldestchara}</div>
                          {$oldest_character} ({$oldest_age})
                   
                </div>	   
						         <div class="po_box">         <div class="po_desc">{$lang->playeroverview_avarageage}</div>
             {$avarage_age}
                </div>
			</div>
			
            <strong>{$lang->playeroverview_scenesprochara}</strong>
            <div class="po_chart">
                <canvas id="charaThreadsChart"></canvas>
            </div>
			
	        <div class="po_grid_overall">
				<div>
            <strong>{$lang->playeroverview_postsprochara}</strong>

            <div class="po_chart po_chart_pie po_character_pie" >

                <canvas id="charaPostsChart"></canvas>

                <div id="charaPostsLegend" class="po_chart_legend"></div>

            </div>
				</div>
				<div>

            <strong>{$lang->playeroverview_charaprogender}</strong>

            <div class="po_chart po_chart_pie">
                <canvas id="genderChart"></canvas>
            </div>
</div>
<div>
            <strong>{$lang->playeroverview_charaprogroup}</strong>

            <div class="po_chart po_chart_pie">
                <canvas id="groupChart"></canvas>
            </div>
				</div>
</div>
            <div class="po_player_grid">
                {$player}
            </div>
       <h1>{$lang->playeroverview_charaoverview}</h1>

			<div class="po_chara_grid">
				{$charas}				
			</div>
        </td>
    </tr>

</table>
{$footer}{$po_js}
</body>
</html>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title' => 'playeroverview_playerstat_charas',
        'template' => $db->escape_string('<div class="po_charabox">
	<div class="po_charaavatar"><img src="{$avatar}" \></div>	
	<div class="po_charaname">{$charaname}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_charaage}</div>
	<div class="po_charafact">{$charaage}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_profession}</div>
		<div class="po_charafact">{$charajob}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_residence}</div>
	<div class="po_charafact">{$chararesidence}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_joinedsince}</div>
		<div class="po_charafact">{$regdate}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_lastseen}</div>
		<div class="po_charafact">{$lastseen}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_inplaystat}</div>
	<div class="po_charafact">{$charainplay}</div>
	<div class="po_charafactpoint">{$lang->playeroverview_lastinplaypost}</div>
		<div class="po_charafact">{$lastinplaypost}</div>
</div>'),
        'sid' => '-2',
        'version' => '',
        'dateline' => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    //CSS einfügen
    $css = array(
        'name' => 'playeroverview.css',
        'tid' => 1,
        'attachedto' => '',
        "stylesheet" => ':root {
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
}',
        'cachefile' => $db->escape_string(str_replace('/', '', 'playeroverview.css')),
        'lastmodified' => time()
    );

    require_once MYBB_ADMIN_DIR . "inc/functions_themes.php";

    $sid = $db->insert_query("themestylesheets", $css);
    $db->update_query("themestylesheets", array("cachefile" => "css.php?stylesheet=" . $sid), "sid = '" . $sid . "'", 1);

    $tids = $db->simple_select("themes", "tid");
    while ($theme = $db->fetch_array($tids)) {
        update_theme_stylesheet_list($theme['tid']);
    }

    // Don't forget this!
    rebuild_settings();
}

function playeroverview_is_installed()
{

    global $mybb;
    if (isset($mybb->settings['playeroverview_playername'])) {
        return true;
    }

    return false;
}

function playeroverview_uninstall()
{
    global $db;

    $db->delete_query('settings', "name IN ('playeroverview_groups','playeroverview_gender','playeroverview_forumbirthday','playeroverview_playername','playeroverview_postfrequence', 'playeroverview_avaragecharacters', 'playeroverview_notcountaccounts','playeroverview_inplaydate')");
    $db->delete_query('settinggroups', "name = 'playeroverview'");
    $db->delete_query("templates", "title LIKE 'playeroverview%'");
    $db->delete_query('templategroups', "prefix = 'playeroverview'");

    // Don't forget this
    rebuild_settings();

    require_once MYBB_ADMIN_DIR . "inc/functions_themes.php";
    $db->delete_query("themestylesheets", "name = 'playeroverview.css'");
    $query = $db->simple_select("themes", "tid");
    while ($theme = $db->fetch_array($query)) {
        update_theme_stylesheet_list($theme['tid']);
        rebuild_settings();
    }

    // Don't forget this
    rebuild_settings();
}

function playeroverview_activate()
{
    require MYBB_ROOT . "/inc/adminfunctions_templates.php";
    find_replace_templatesets("header", "#" . preg_quote('{$menu_portal}') . "#i", '{$menu_playeroverview}{$menu_portal}');
    find_replace_templatesets("headerinclude", "#" . preg_quote('{$stylesheets}') . "#i", '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>{$stylesheets}');
}

function playeroverview_deactivate()
{
    require MYBB_ROOT . "/inc/adminfunctions_templates.php";
    find_replace_templatesets("header", "#" . preg_quote('{$menu_playeroverview}') . "#i", '', 0);
    find_replace_templatesets("header", "#" . preg_quote('<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>') . "#i", '', 0);
}

$plugins->add_hook("global_intermediate", "playeroverview_global");
function playeroverview_global()
{
    global $db, $mybb, $templates, $lang, $menu_playeroverview;
    $lang->load("playeroverview");
    eval("\$menu_playeroverview = \"" . $templates->get("playeroverview_header") . "\";");
}


$plugins->add_hook('misc_start', 'playeroverview_misc');

// In the body of your plugin
function playeroverview_misc()
{
    global $mybb, $templates, $lang, $header, $headerinclude, $footer, $db, $theme;
    $lang->load('playeroverview');

    // Einstellungen
    $forumbirthday = $mybb->settings['playeroverview_forumbirthday'];
    $playernamefid = $mybb->settings['playeroverview_playername'];
    $nocountaccounts = $mybb->settings['playeroverview_notcountaccounts'];
    $postcharacters = $mybb->settings['playeroverview_avaragecharacters'];
    $postfrequence = $mybb->settings['playeroverview_postfrequence'];
    $charagender = $mybb->settings['playeroverview_gender'];
    $groups = $mybb->settings['playeroverview_groups'];

    // Settings vom tracker ziehen
    $inplay_cat = $mybb->settings['ipt_inplay_id'];
    $archive_forum = $mybb->settings['ipt_archive_id'];
    $messager_forum = $mybb->settings['messager_forum'];

    // Settings vom Inplaykalender ziehen
    $newyear = $mybb->settings['ic_newyear'];
    $inplayyear = $mybb->settings['ic_year'];
    $secondyear = $mybb->settings['ic_secondyear'];

    if ($mybb->get_input('action') == 'forumoverview') {
        // Do something, for example I'll create a page using the hello_world_template

        // Add a breadcrumb
        add_breadcrumb($lang->forumoverview, "misc.php?action=forumoverview");


        // Forenstatistik berechnen

        // Forengeburtstag
        // aus dem Tutorial von aheartforspinach
        $fulldate = "";
        $days = 0;
        $day = 0;
        $month = 0;
        $year = 0;
        $firstDate = 0;
        $secondDate = 0;

        $firstDate  = new DateTime($forumbirthday);
        $secondDate = new DateTime(date("Y-m-d", time()));
        $intvl = $firstDate->diff($secondDate);

        $days = $intvl->days;
        $year = $intvl->y;
        $month = $intvl->m;
        $day = $intvl->d;
        if ($year == 0 or $year > 1) {
            $year_text = $lang->sprintf($lang->forumoverview_years, $year);
        } else if ($year == 1) {
            $year_text = $lang->sprintf($lang->forumoverview_year, $year);
        }

        if ($month == 0 or $month > 1) {
            $month_text = $lang->sprintf($lang->forumoverview_months, $month);
        } else if ($month == 1) {
            $month_text =  $lang->sprintf($lang->forumoverview_month, $month);
        }

        if ($day == 0 or $day > 1) {
            $day_text =  $lang->sprintf($lang->forumoverview_days, $day);
        } else if ($day == 1) {
            $day_text =  $lang->sprintf($lang->forumoverview_day, $day);
        }

        $fulldate = $lang->sprintf($lang->forumoverview_fulldate, $year_text, $month_text, $day_text);
        $days = $lang->sprintf($lang->forumoverview_countdays, $days);

        // eröffnungsdatum sauber darstellen
        $date = explode("-", $forumbirthday);
        $opendate = $lang->sprintf($lang->forumoverview_opendate, $date[2], $date[1], $date[0]);

        // Szenen berechnen
        $count_threads = 0;
        $count_posts = 0;
        $averageWords = 0;
        $averageCharacters = 0;

        $query = $db->query("SELECT *
    FROM " . TABLE_PREFIX . "threads t
    LEFT JOIN " . TABLE_PREFIX . "forums f
    on (t.fid = f.fid)    
    where f.parentlist like '" . $inplay_cat . ",%'
    OR concat(',',f.parentlist,',') LIKE '%," . $archive_forum . ",%' 

    ");

        while ($get_count = $db->fetch_array($query)) {
            if ($get_count['fid'] != $messager_forum) {
                $count_threads++;
            }
        }

        $query = $db->query("SELECT *
    FROM " . TABLE_PREFIX . "posts p
    LEFT JOIN " . TABLE_PREFIX . "threads t
        on (t.tid = p.tid)
        LEFT JOIN " . TABLE_PREFIX . "forums f
        on (t.fid = f.fid)
        where f.parentlist like '" . $inplay_cat . ",%'
        OR concat(',',f.parentlist,',') LIKE '%," . $archive_forum . ",%' 
    ");

        while ($get_count = $db->fetch_array($query)) {
            if ($get_count['fid'] != $messager_forum) {
                $count_posts++;
                // aus dem Tutorial von aheartforspinach
                // https://www.php.net/manual/en/function.str-word-count.php#107363
                $words += count(preg_split('~[^\p{L}\p{N}\']+~u', $get_count['message']));
                $characters += strlen($get_count['message']);
            }
        }

        // aus dem Tutorial von aheartforspinach
        $averageWords =  round($words / $count_posts, 2);
        $averageCharacters =  round($characters / $count_posts, 2);

        $countplayer = 0;
        $countcharas = 0;
        $avaragecharas = 0;
        $where = "WHERE as_uid = 0";

        if (!empty($nocountaccounts)) {
            $where .= " AND NOT FIND_IN_SET(uid, '$nocountaccounts')";
        }

        $get_user = $db->query("SELECT COUNT(*) AS count
            FROM " . TABLE_PREFIX . "users
            $where
            ");

        $countplayer = $db->fetch_field($get_user, "count");

        $where = "";
        if (!empty($nocountaccounts)) {
            $where .= " WHERE NOT FIND_IN_SET(uid, '$nocountaccounts')";
        }

        $get_charas = $db->query("SELECT COUNT(*) AS count
            FROM " . TABLE_PREFIX . "users
            $where
            ");


        $countcharas = $db->fetch_field($get_charas, "count");


        $avaragecharas = round($countcharas / $countplayer, 2);

        // Diagramme

        /*
 * Chart-Variablen für gemeinsames Template
 */

        $chara_labels = '[]';
        $chara_threads = '[]';

        $chara_post_labels = '[]';
        $chara_posts = '[]';

        /*
 * Geschlecht aller Charaktere
 */
        $gender_data = [];

        $get_gender = $db->query("
    SELECT uf." . $charagender . " AS gender
    FROM " . TABLE_PREFIX . "users u
    INNER JOIN " . TABLE_PREFIX . "userfields uf
        ON uf.ufid = u.uid
    WHERE NOT FIND_IN_SET(
        u.uid,
        '" . $db->escape_string($nocountaccounts) . "'
    )
");

        while ($gender = $db->fetch_array($get_gender)) {

            $value = trim($gender['gender']);

            /*
     * Leere Angaben und "Keine Angabe" nicht zählen
     */
            if (
                $value === '' ||
                mb_strtolower($value) === 'keine angabe'
            ) {
                continue;
            }

            if (!isset($gender_data[$value])) {
                $gender_data[$value] = 0;
            }

            $gender_data[$value]++;
        }


        $gender_labels = json_encode(array_keys($gender_data));
        $gender_values = json_encode(array_values($gender_data));

        /*
 * Gruppen aller Charaktere
 */
        $group_data = [];
        $group_labels = [];
        $group_values = [];
        $group_colors = [];

        if (trim((string)$groups) !== '') {

            /*
     * Welche Gruppen dürfen ausgewertet werden?
     */
            if (trim((string)$groups) === '-1') {

                $allowed_groups = null;
            } else {

                $allowed_groups = array_map(
                    'intval',
                    array_filter(
                        explode(',', (string)$groups),
                        'strlen'
                    )
                );
            }


            /*
     * Alle Charaktere laden
     */
            $get_group_users = $db->query("
        SELECT uid, usergroup, additionalgroups
        FROM " . TABLE_PREFIX . "users
        WHERE NOT FIND_IN_SET(
            uid,
            '" . $db->escape_string($nocountaccounts) . "'
        )
    ");


            while ($user = $db->fetch_array($get_group_users)) {

                $user_groups = [];

                /*
         * Primäre Gruppe
         */
                $user_groups[] = (int)$user['usergroup'];


                /*
         * Zusätzliche Gruppen
         */
                if (!empty($user['additionalgroups'])) {

                    $user_groups = array_merge(
                        $user_groups,
                        array_map(
                            'intval',
                            explode(',', $user['additionalgroups'])
                        )
                    );
                }


                /*
         * Doppelte Gruppen beim selben Charakter entfernen
         */
                $user_groups = array_unique($user_groups);


                foreach ($user_groups as $group_id) {

                    /*
             * Ungültige Gruppe ignorieren
             */
                    if ($group_id <= 0) {
                        continue;
                    }


                    /*
             * Falls nur bestimmte Gruppen erlaubt sind
             */
                    if (
                        $allowed_groups !== null &&
                        !in_array($group_id, $allowed_groups, true)
                    ) {
                        continue;
                    }


                    if (!isset($group_data[$group_id])) {
                        $group_data[$group_id] = 0;
                    }

                    $group_data[$group_id]++;
                }
            }


            /*
     * Gruppennamen aus der Usergroups-Tabelle holen
     */
            if (!empty($group_data)) {

                $group_ids = implode(
                    ',',
                    array_map(
                        'intval',
                        array_keys($group_data)
                    )
                );


                $get_group_names = $db->query("
            SELECT gid, title
            FROM " . TABLE_PREFIX . "usergroups
            WHERE gid IN ({$group_ids})
        ");


                while ($group = $db->fetch_array($get_group_names)) {

                    $gid = (int)$group['gid'];

                    $group_labels[] = $group['title'];
                    $group_values[] = $group_data[$gid];

                    /*
             * Wichtig für die CSS-Farben
             */
                    $group_colors[] = $gid;
                }
            }
        }


        $group_labels = json_encode($group_labels);
        $group_values = json_encode($group_values);
        $group_colors = json_encode($group_colors);
        /*
 * Altersverteilung aller Charaktere
 */

        $age_data = [];


        /*
 * Inplaydatum
 */
        $inplaydate = $mybb->settings['playeroverview_inplaydate'];

        $inplay_date = explode(".", $inplaydate);

        $inplayyear = $inplay_date[2];

        $rep_ipyear = array(2 => $inplayyear);

        $inplay_date = array_replace(
            $inplay_date,
            $rep_ipyear
        );

        $lastday = implode(
            ".",
            $inplay_date
        );

        $inplay = new DateTime($lastday);


        /*
 * Alle Charaktere
 * Accounts aus $nocountaccounts werden ausgeschlossen
 */
        $get_character_ages = $db->query("
    SELECT birthday
    FROM " . TABLE_PREFIX . "users
    WHERE NOT FIND_IN_SET(
        uid,
        '" . $db->escape_string($nocountaccounts) . "'
    )
");


        while ($chara = $db->fetch_array($get_character_ages)) {

            /*
     * Kein Geburtstag vorhanden
     */
            if (empty($chara['birthday'])) {
                continue;
            }


            /*
     * Geburtstag auseinandernehmen
     */
            $explode_birthday = explode(
                "-",
                $chara['birthday']
            );


            if (count($explode_birthday) !== 3) {
                continue;
            }


            $birth_day = $explode_birthday[0];
            $birth_month = $explode_birthday[1];
            $birth_year = $explode_birthday[2];


            /*
     * Geburtsjahr auf vier Stellen bringen
     */
            $birthyear = str_pad(
                $birth_year,
                4,
                "0",
                STR_PAD_LEFT
            );


            try {

                /*
         * Geburtstag erstellen
         */
                $charabirthday = new DateTime(
                    $birth_day . "." .
                        $birth_month . "." .
                        $birthyear
                );


                /*
         * Alter zum Inplaydatum
         */
                $interval = $inplay->diff(
                    $charabirthday
                );

                $charaage = (int)$interval->format("%Y");


                /*
         * Alter zählen
         */
                if (!isset($age_data[$charaage])) {
                    $age_data[$charaage] = 0;
                }

                $age_data[$charaage]++;
            } catch (Exception $e) {

                // Ungültiges Geburtsdatum ignorieren
            }
        }


        /*
 * Nach Alter sortieren
 */
        ksort(
            $age_data,
            SORT_NUMERIC
        );


        /*
 * Chart-Daten
 */
        $age_labels = json_encode(
            array_keys($age_data)
        );

        $age_values = json_encode(
            array_values($age_data)
        );


        // Using the misc_help template for the page wrapper
        eval("\$po_js = \"" . $templates->get("playeroverview_javascript") . "\";");
        eval("\$page = \"" . $templates->get("playeroverview_forenstatistic") . "\";");
        output_page($page);
    }

    // Spielerübersicht
    if ($mybb->get_input('action') == 'playeroverview') {
        // Do something, for example I'll create a page using the hello_world_template

        // Add a breadcrumb
        add_breadcrumb($lang->playeroverview, "misc.php?action=playeroverview");


        $countplayer = 0;
        $countcharas = 0;
        $avaragecharas = 0;
        $where = "WHERE as_uid = 0";

        if (!empty($nocountaccounts)) {
            $where .= " AND NOT FIND_IN_SET(uid, '$nocountaccounts')";
        }

        $get_user = $db->query("SELECT COUNT(*) AS count
            FROM " . TABLE_PREFIX . "users
            $where
            ");

        $countplayer = $db->fetch_field($get_user, "count");

        $where = "";
        if (!empty($nocountaccounts)) {
            $where .= " WHERE NOT FIND_IN_SET(uid, '$nocountaccounts')";
        }

        $get_charas = $db->query("SELECT COUNT(*) AS count
            FROM " . TABLE_PREFIX . "users
            $where
            ");


        $countcharas = $db->fetch_field($get_charas, "count");


        $avaragecharas = round($countcharas / $countplayer, 2);


        $count_threads = 0;
        $count_posts = 0;
        $avaragescene = 0;

        $query = $db->query("SELECT *
    FROM " . TABLE_PREFIX . "threads t
    LEFT JOIN " . TABLE_PREFIX . "forums f
    on (t.fid = f.fid)    
    where f.parentlist like '" . $inplay_cat . ",%'
    OR concat(',',f.parentlist,',') LIKE '%," . $archive_forum . ",%' 

    ");

        while ($get_count = $db->fetch_array($query)) {
            if ($get_count['fid'] != $messager_forum) {
                $count_threads++;
            }
        }

        $query = $db->query("SELECT *
    FROM " . TABLE_PREFIX . "posts p
    LEFT JOIN " . TABLE_PREFIX . "threads t
        on (t.tid = p.tid)
        LEFT JOIN " . TABLE_PREFIX . "forums f
        on (t.fid = f.fid)
        where f.parentlist like '" . $inplay_cat . ",%'
        OR concat(',',f.parentlist,',') LIKE '%," . $archive_forum . ",%' 
    ");

        while ($get_count = $db->fetch_array($query)) {
            if ($get_count['fid'] != $messager_forum) {
                $count_posts++;
            }
        }

        $avaragescene = round($count_threads / $countcharas, 2);

        // Spielerinfos ziehen
        $where = "WHERE as_uid = 0";

        if (!empty($nocountaccounts)) {
            $where .= " AND NOT FIND_IN_SET(uid, '$nocountaccounts')";
        }

        $get_user = $db->query("SELECT *
        FROM " . TABLE_PREFIX . "users u
        LEFT JOIN " . TABLE_PREFIX . "userfields uf
        on (u.uid = uf.ufid)
        $where
        ORDER BY $playernamefid ASC
        ");

        while ($user = $db->fetch_array($get_user)) {
            $playername = "";
            $lastseen = "";
            $regdate = "";
            $uid = 0;
            $$playerinplay = "";
            $postcount = 0;
            $threadcount = 0;

            $uid = $user['uid'];

            if (!empty($user[$playernamefid])) {
                $playername = $user[$playernamefid];
            } else {
                $playername = $lang->playeroverview_noname;
            }

            $regdate = date("d.m.Y", $user['regdate']);

            // hol die letzte aktivität
            $get_lastactive = $db->query("
            SELECT MAX(lastactive) AS lastactive
            FROM " . TABLE_PREFIX . "users
            WHERE uid = " . (int)$uid . "
            OR as_uid = " . (int)$uid . "
        ");
            $lastactive = $db->fetch_field($get_lastactive, "lastactive");

            if ($lastactive != 0) {
                $lastseen = date("d.m.Y", $lastactive);
            } else {
                $lastseen = $lang->playeroverview_notseen;
            }


            // Posts zählen
            $get_posts = $db->query("
    SELECT COUNT(p.pid) AS postcount
    FROM " . TABLE_PREFIX . "posts p
    INNER JOIN " . TABLE_PREFIX . "users u
        ON u.uid = p.uid
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = p.fid
    WHERE (u.uid = " . (int)$uid . " OR u.as_uid = " . (int)$uid . ")
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

            $postcount = $db->fetch_field($get_posts, "postcount");


            // Threads zählen
            $get_threads = $db->query("
    SELECT COUNT(DISTINCT t.tid) AS threadcount
    FROM " . TABLE_PREFIX . "threads t
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = t.fid
    LEFT JOIN " . TABLE_PREFIX . "users u
        ON u.uid = " . (int)$uid . "
        OR u.as_uid = " . (int)$uid . "
    WHERE (
        t.uid = u.uid
        OR FIND_IN_SET(u.username, t.charas)
    )
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

            $threadcount = $db->fetch_field($get_threads, "threadcount");
            if ($postcount == 1) {
                $post = $lang->playeroverview_post;
            } else {
                $post = $lang->playeroverview_posts;
            }

            if ($threadcount == 1) {
                $scene = $lang->playeroverview_scene;
            } else {
                $scene = $lang->playeroverview_scenes;
            }
            $playerinplay = $lang->sprintf($lang->playeroverview_inplay, $postcount, $post, $threadcount, $scene);


            // alle Charaktere ausgeben

            $usercharas = 0;
            $charaheadline = "";
            $charas = "";
            $get_charas = $db->query("SELECT *
            FROM " . TABLE_PREFIX . "users
            where uid = " . (int)$uid . "
        OR as_uid = " . (int)$uid . "
        order by username ASC
            ");

            while ($chara = $db->fetch_array($get_charas)) {
                $usercharas++;
                $avatar = "";
                $charaname = "";
                $charalastseen = "";

                if ($mybb->user['uid'] == 0 or empty($chara['avatar'])) {
                    $avatar = "{$theme['imgdir']}/noavatar.png";
                } else {
                    $avatar = $chara['avatar'];
                }

                $username = format_name($chara['username'], $chara['usergroup'], $chara['displaygroup']);
                $charaname = build_profile_link($username, $chara['uid']);

                // last activity

                if ($chara['lastactive'] != 0) {
                    $charalastseen = $lang->sprintf($lang->playeroverview_lastactive, date("d.m.Y, H:m", $chara['lastactive']));
                } else {
                    $charalastseen = $lang->playeroverview_notseen;
                }

                eval("\$charas .= \"" . $templates->get("playeroverview_playeroverview_player_charas") . "\";");
            }

            $playerstat = $lang->sprintf($lang->playoverview_playerstat_link, $uid);

            if ($usercharas == 1) {
                $charaheadline = $lang->sprintf($lang->playeroverview_chara, $usercharas);
            } else {
                $charaheadline = $lang->sprintf($lang->playeroverview_charas, $usercharas);
            }
            eval("\$player .= \"" . $templates->get("playeroverview_playeroverview_player") . "\";");
        }

        // Using the misc_help template for the page wrapper
        eval("\$page = \"" . $templates->get("playeroverview_playeroverview") . "\";");
        output_page($page);
    }

    // Spielerübersicht
    if ($mybb->get_input('action') == 'player') {
        // Do something, for example I'll create a page using the hello_world_template

        $get_uid = $mybb->input['uid'];


        $get_playername = $db->query("SELECT {$playernamefid} as playername
            FROM " . TABLE_PREFIX . "userfields
            where ufid = {$get_uid}
            ");

        $playername = $db->fetch_field($get_playername, "playername");
        $showplayeroverview = $lang->sprintf($lang->playeroverview_show, $playername);

        // Add a breadcrumb
        add_breadcrumb($lang->playeroverview, "misc.php?action=playeroverview");
        add_breadcrumb($showplayeroverview);

        $user_regdate = 0;
        $lastactive = 0;
        $avaragescene = 0;
        $characount = 0;

        // Spielerfakten ziehen :D

        $get_user = $db->simple_select(
            "users",
            "regdate",
            "uid = {$get_uid}"
        );

        $regdate = $db->fetch_field($get_user, "regdate");

        $user_regdate = date("d.m.Y", $regdate);

        $get_user = $db->simple_select(
            "users",
            "lastactive",
            "uid = {$get_uid} or as_uid = {$get_uid}",
            array(
                "order_by" => 'lastactive',
                "order_dir" => 'DESC',
                "limit" => 1
            )
        );
        $lastactive = $db->fetch_field($get_user, "lastactive");

        $lastactive = date("d.m.Y", $lastactive);

        $get_user = $db->simple_select(
            "users",
            "COUNT(*) as characount",
            "uid = {$get_uid} or as_uid = {$get_uid}"
        );

        $characount = $db->fetch_field($get_user, "characount");

        // Postfrequenz und Zeichenzahl ziehen

        $get_userfields = $db->simple_select(
            "userfields",
            "*",
            "ufid = {$get_uid}"
        );
        $row = $db->fetch_array($get_userfields);

        $postfrequence = $row[$postfrequence];
        $postcharacters = $row[$postcharacters];
        // Posts, Wörter und Zeichen zählen
        $get_posts = $db->query("
    SELECT p.message
    FROM " . TABLE_PREFIX . "posts p
    INNER JOIN " . TABLE_PREFIX . "users u
        ON u.uid = p.uid
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = p.fid
    WHERE (
        u.uid = " . (int)$get_uid . "
        OR u.as_uid = " . (int)$get_uid . "
    )
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

        $postcount = 0;
        $wordcount = 0;
        $charactercount = 0;

        while ($post = $db->fetch_array($get_posts)) {

            $message = $post['message'];

            // HTML entfernen
            $message = strip_tags($message);

            // BBCode entfernen
            $message = preg_replace('/\[[^\]]*\]/', '', $message);

            // Leerzeichen und Zeilenumbrüche vereinheitlichen
            $message = preg_replace('/\s+/', ' ', $message);

            // Anfang und Ende bereinigen
            $message = trim($message);

            // Post zählen
            $postcount++;

            // Wörter zählen
            if ($message !== '') {
                $wordcount += str_word_count(
                    $message,
                    0,
                    'ÄÖÜäöüß'
                );
            }

            // Zeichen ohne Leerzeichen zählen
            $charactercount += mb_strlen(
                preg_replace('/\s+/', '', $message),
                'UTF-8'
            );
        }

        // Durchschnittliche Wörter pro Post
        $averagewords = $postcount > 0
            ? round($wordcount / $postcount, 2)
            : 0;


        // Threads zählen
        $get_threads = $db->query("
    SELECT COUNT(DISTINCT t.tid) AS threadcount
    FROM " . TABLE_PREFIX . "threads t
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = t.fid
    LEFT JOIN " . TABLE_PREFIX . "users u
        ON u.uid = " . (int)$get_uid . "
        OR u.as_uid = " . (int)$get_uid . "
    WHERE (
        t.uid = u.uid
        OR FIND_IN_SET(u.username, t.charas)
    )
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

        $threadcount = (int)$db->fetch_field($get_threads, "threadcount");

        $avaragescene = $characount > 0
            ? round($threadcount / $characount, 2)
            : 0;


        $get_lastpost = $db->query("
    SELECT 
        p.pid,
        p.dateline,
        t.tid,
        t.subject
    FROM " . TABLE_PREFIX . "posts p
    INNER JOIN " . TABLE_PREFIX . "threads t
        ON t.tid = p.tid
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = t.fid
    INNER JOIN " . TABLE_PREFIX . "users u
        ON u.uid = p.uid
    WHERE (
        u.uid = " . (int)$get_uid . "
        OR u.as_uid = " . (int)$get_uid . "
    )
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
    ORDER BY p.dateline DESC
    LIMIT 1
");

        if ($lastpost = $db->fetch_array($get_lastpost)) {

            $lastpost_subject = htmlspecialchars_uni($lastpost['subject']);

            $lastpost_link = get_thread_link(
                $lastpost['tid'],
                '',
                'lastpost'
            );

            $lastpost_date = my_date(
                'd.m.Y, H:i',
                $lastpost['dateline']
            );
        } else {

            $lastpost_subject = '-';
            $lastpost_link = '#';
            $lastpost_date = '-';
        }

        // Threads pro Charakter für das Diagramm
        $get_charas = $db->query("
    SELECT *
    FROM " . TABLE_PREFIX . "users
    WHERE uid = " . (int)$get_uid . "
       OR as_uid = " . (int)$get_uid . "
");

        $chara_labels = [];
        $chara_threads = [];

        while ($chara = $db->fetch_array($get_charas)) {

            $chara_uid = (int)$chara['uid'];
            $chara_name = $chara['username'];
            $chara_name_escaped = $db->escape_string($chara_name);

            $get_chara_threads = $db->query("
        SELECT COUNT(DISTINCT t.tid) AS threadcount
        FROM " . TABLE_PREFIX . "threads t
        INNER JOIN " . TABLE_PREFIX . "forums f
            ON f.fid = t.fid
        WHERE (
            t.uid = {$chara_uid}
            OR FIND_IN_SET('{$chara_name_escaped}', t.charas)
        )
        AND (
            f.parentlist LIKE '" . $inplay_cat . ",%'
            OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
        )
    ");

            $chara_threadcount = (int)$db->fetch_field(
                $get_chara_threads,
                'threadcount'
            );

            $chara_labels[] = $chara_name;
            $chara_threads[] = $chara_threadcount;
        }

        $chara_labels = json_encode(
            $chara_labels,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        $chara_threads = json_encode($chara_threads);


        // Posts pro Charakter für das Diagramm
        $get_charas = $db->query("
    SELECT *
    FROM " . TABLE_PREFIX . "users
    WHERE uid = " . (int)$get_uid . "
       OR as_uid = " . (int)$get_uid . "
");

        $chara_post_labels = [];
        $chara_posts = [];

        while ($chara = $db->fetch_array($get_charas)) {

            $chara_uid = (int)$chara['uid'];
            $chara_name = $chara['username'];
            /*
 * Inplaydatum für Altersberechnung
 */
            $inplaydate = $mybb->settings['playeroverview_inplaydate'];

            $inplay_date = explode(".", $inplaydate);
            $inplayyear = $inplay_date[2];

            $rep_ipyear = array(2 => $inplayyear);
            $inplay_date = array_replace($inplay_date, $rep_ipyear);

            $lastday = implode(".", $inplay_date);
            $inplay = new DateTime($lastday);


            /*
 * Charakterstatistiken
 */
            $first_character = '-';
            $newest_character = '-';

            $youngest_character = '-';
            $youngest_age = null;

            $oldest_character = '-';
            $oldest_age = null;

            $total_age = 0;
            $age_count = 0;

            $first_regdate = null;
            $newest_regdate = null;

            $get_character_stats = $db->query("
    SELECT uid, username, regdate, birthday
    FROM " . TABLE_PREFIX . "users
    WHERE uid = " . (int)$get_uid . "
       OR as_uid = " . (int)$get_uid . "
");



            while ($chara = $db->fetch_array($get_character_stats)) {

                /*
     * Erster / neuester Charakter
     */
                $regdate = (int)$chara['regdate'];

                if ($first_regdate === null || $regdate < $first_regdate) {

                    $first_regdate = $regdate;
                    $first_character = $chara['username'];
                }

                if ($newest_regdate === null || $regdate > $newest_regdate) {

                    $newest_regdate = $regdate;
                    $newest_character = $chara['username'];
                }


                /*
     * Alter berechnen
     */
                if (!empty($chara['birthday'])) {

                    $explode_birthday = explode("-", $chara['birthday']);

                    if (count($explode_birthday) === 3) {

                        $birth_day = $explode_birthday[0];
                        $birth_month = $explode_birthday[1];
                        $birth_year = $explode_birthday[2];

                        /*
             * Geburtsjahr auf vier Stellen bringen
             */
                        $birthyear = str_pad(
                            $birth_year,
                            4,
                            "0",
                            STR_PAD_LEFT
                        );


                        try {

                            $charabirthday = new DateTime(
                                $birth_day . "." .
                                    $birth_month . "." .
                                    $birthyear
                            );

                            /*
                 * Alter zum Inplaydatum
                 */
                            $interval = $inplay->diff($charabirthday);

                            $charaage = (int)$interval->format("%Y");


                            /*
                 * Gesamtalter
                 */
                            $total_age += $charaage;
                            $age_count++;


                            /*
                 * Jüngster Charakter
                 */
                            if (
                                $youngest_age === null ||
                                $charaage < $youngest_age
                            ) {

                                $youngest_age = $charaage;
                                $youngest_character = $chara['username'];
                            }


                            /*
                 * Ältester Charakter
                 */
                            if (
                                $oldest_age === null ||
                                $charaage > $oldest_age
                            ) {

                                $oldest_age = $charaage;
                                $oldest_character = $chara['username'];
                            }
                        } catch (Exception $e) {
                            // Ungültiges Geburtsdatum ignorieren
                        }
                    }
                }
            }


            /*
 * Durchschnittsalter
 */
            if ($age_count > 0) {

                $average_age = round($total_age / $age_count);
            } else {

                $average_age = null;
            }


            /*
 * Alter formatieren
 */
            if ($youngest_age !== null) {

                $youngest_age = $lang->sprintf(
                    $lang->playeroverview_age,
                    $youngest_age
                );
            }

            if ($oldest_age !== null) {

                $oldest_age = $lang->sprintf(
                    $lang->playeroverview_age,
                    $oldest_age
                );
            }

            if ($average_age !== null) {

                $average_age = $lang->sprintf(
                    $lang->playeroverview_age,
                    $average_age
                );
            }


            /*
 * Ausgabe für die Boxen
 */
            if ($youngest_character !== '-') {

                $show_youngest = $youngest_character . " (" . $youngest_age . ")";
            } else {

                $show_youngest = '-';
            }


            if ($oldest_character !== '-') {

                $show_oldest = $oldest_character . " (" . $oldest_age . ")";
            } else {

                $show_oldest = '-';
            }

            /*
 * Durchschnittsalter
 */
            if ($age_count > 0) {

                $average_age = round(
                    $total_age / $age_count
                );
                $avarage_age =  $lang->sprintf($lang->playeroverview_age, $average_age);
            } else {

                $average_age = '-';
            }


            // Diagramme

            /*
 * Chart-Variablen für gemeinsames Template
 */

            $age_labels = '[]';
            $age_values = '[]';

            // Posts der Charaktere zählen
            $get_chara_posts = $db->query("
        SELECT COUNT(*) AS postcount
        FROM " . TABLE_PREFIX . "posts p
        INNER JOIN " . TABLE_PREFIX . "threads t
            ON t.tid = p.tid
        INNER JOIN " . TABLE_PREFIX . "forums f
            ON f.fid = t.fid
        WHERE p.uid = {$chara_uid}
        AND (
            f.parentlist LIKE '" . $inplay_cat . ",%'
            OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
        )
    ");

            $chara_postcount = (int)$db->fetch_field(
                $get_chara_posts,
                'postcount'
            );

            $chara_post_labels[] = $chara_name;
            $chara_posts[] = $chara_postcount;
        }

        $chara_post_labels = json_encode(
            $chara_post_labels,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        $chara_posts = json_encode($chara_posts);

        $get_gender = $db->query("
    SELECT uf." . $charagender . " AS gender
    FROM " . TABLE_PREFIX . "users u
    INNER JOIN " . TABLE_PREFIX . "userfields uf
        ON uf.ufid = u.uid
    WHERE u.uid = " . (int)$get_uid . "
       OR u.as_uid = " . (int)$get_uid . "
");

        $gender_data = [];

        while ($gender = $db->fetch_array($get_gender)) {

            $value = trim($gender['gender']);

            // Keine Angabe nicht berücksichtigen
            if (
                $value === '' ||
                mb_strtolower($value) === 'keine angabe' || mb_strtolower($value) === 'Keine Angabe'
            ) {
                continue;
            }

            if (!isset($gender_data[$value])) {
                $gender_data[$value] = 0;
            }

            $gender_data[$value]++;
        }

        $gender_labels = json_encode(array_keys($gender_data));
        $gender_values = json_encode(array_values($gender_data));

        $group_data = [];
        $group_labels = [];
        $group_values = [];
        $group_colors = [];

        if (trim((string)$groups) !== '') {

            // Welche Gruppen sollen berücksichtigt werden?
            if (trim((string)$groups) === '-1') {
                $allowed_groups = null; // alle Gruppen
            } else {
                $allowed_groups = array_map(
                    'intval',
                    array_filter(
                        explode(',', (string)$groups),
                        'strlen'
                    )
                );
            }

            $get_group_users = $db->query("
        SELECT uid, usergroup, additionalgroups
        FROM " . TABLE_PREFIX . "users
        WHERE uid = " . (int)$get_uid . "
           OR as_uid = " . (int)$get_uid . "
    ");

            while ($user = $db->fetch_array($get_group_users)) {

                $user_groups = [];

                // Hauptgruppe
                $user_groups[] = (int)$user['usergroup'];

                // Zusätzliche Gruppen
                if (!empty($user['additionalgroups'])) {
                    $user_groups = array_merge(
                        $user_groups,
                        array_map(
                            'intval',
                            explode(',', $user['additionalgroups'])
                        )
                    );
                }

                $user_groups = array_unique($user_groups);

                foreach ($user_groups as $group_id) {

                    if ($group_id <= 0) {
                        continue;
                    }

                    // Bei -1 alle Gruppen, sonst nur die ausgewählten
                    if (
                        $allowed_groups !== null &&
                        !in_array($group_id, $allowed_groups, true)
                    ) {
                        continue;
                    }

                    if (!isset($group_data[$group_id])) {
                        $group_data[$group_id] = 0;
                    }

                    $group_data[$group_id]++;
                }
            }

            // Gruppennamen holen
            if (!empty($group_data)) {

                $group_ids = implode(',', array_map('intval', array_keys($group_data)));

                $get_group_names = $db->query("
            SELECT gid, title
            FROM " . TABLE_PREFIX . "usergroups
            WHERE gid IN ({$group_ids})
        ");

                while ($group = $db->fetch_array($get_group_names)) {

                    $gid = (int)$group['gid'];

                    $group_labels[] = $group['title'];
                    $group_values[] = $group_data[$gid];

                    $group_colors[] = $gid;
                }
            }
        }

        // Für JavaScript
        $group_labels = json_encode($group_labels);
        $group_values = json_encode($group_values);
        $group_colors = json_encode($group_colors);

        $age_labels = '[]';
        $age_values = '[]';

        // hol alle Charaktere und lese sie einzeln aus
        $get_allcharas = $db->query("SELECT *
        FROM " . TABLE_PREFIX . "users
               WHERE uid = " . (int)$get_uid . "
           OR as_uid = " . (int)$get_uid . "
           ORDER BY username ASC
        ");

        while ($chara = $db->fetch_array($get_allcharas)) {
            $charaname = "";
            $avatar = "";
            $charaage  = "";
            $charajob = "";
            $chararesidence = "";
            $regdate = "";
            $lastseen = "";
            $uid = 0;
            $charainplay = "";
            $r_level = "";
            $lastinplaypost = "";

            $uid = $chara['uid'];

            $username = format_name($chara['username'], $chara['usergroup'], $chara['displaygroup']);
            $charaname = build_profile_link($username, $chara['uid']);


            if ($mybb->user['uid'] == 0 || empty($chara['avatar'])) {
                $avatar = "{$theme['imgdir']}/noavatar.png";
            } else {
                $avatar = $chara['avatar'];
            }

            if (!empty($chara['birthday'])) {

                $explode_birthday = explode("-", $chara['birthday']);

                // nun ist jeder Part vom Geburtstag ein eigener Array eingetrag. Jetzt zieh ich sie mir einzeln. Arrays beginnen immer bei 0, weswegen die erste Position die 0 ist.
                $birth_day = $explode_birthday[0];
                $birth_month = $explode_birthday[1];
                $birth_year = $explode_birthday[2];
                $birth_year_count = strlen($birth_year);

                // dann gucken wir mal, ob das Geburtsjahr 4 Ziffern hat, sonst müssen wir auffüllen.
                if ($birth_year_count < 4) {
                    $zero = "";
                    for ($i = $birth_year_count; $i <= 3; $i++) {
                        $zero = "0";
                    }
                    $birthyear = $zero . $birth_year;
                } else {
                    $birthyear = $birth_year;
                }

                // wir formatieren Geburtstag neu :) und berechnen den Geburtstag daraus.

                $charabirthday = new DateTime($birth_day . "." . $birth_month . "." . $birthyear);
                $interval = $inplay->diff($charabirthday);
                $charaage = $interval->format("%Y Jahre");
            } else {
                $charaage = $lang->playeroverview_ka;
            }

            // charakterberuf holen
            if (!empty($chara['jtitle'])) {
                $charajob = $chara['jtitle'];
            } else {
                $charajob = $lang->playeroverview_ka;
            }

            // Wohnort holen
            $get_residence = $db->fetch_array($db->simple_select("residences", "*", "rid ='{$chara['rid']}'"));

            if (!empty($chara['r_level'])) {
                $r_level = $lang->sprintf($lang->playeroverview_residence_level, $chara['r_level']);
            }

            if (!empty($get_residence)) {
                $chararesidence = $get_residence['street'] . " " . $get_residence['housenumber'] . $r_level;
            } else {
                $chararesidence = $lang->playeroverview_ka;
            }

            // Anmeldedatum und zuletzt gesehen
            $regdate = date("d.m.Y", $chara['regdate']);

            if (!empty($chara['lastactive'])) {
                $lastseen = date("d.m.Y", $chara['lastactive']);
            } else {
                $lastseen = $lang->playeroverview_notseen;
            }

            // Posts und Szenen
            // Posts des Charakters zählen
            $get_posts = $db->query("
    SELECT COUNT(p.pid) AS postcount
    FROM " . TABLE_PREFIX . "posts p
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = p.fid
    WHERE p.uid = " . (int)$uid . "
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

            $postcount = (int)$db->fetch_field($get_posts, "postcount");

            // Szenen des Charakters zählen
            $get_threads = $db->query("
    SELECT COUNT(DISTINCT t.tid) AS threadcount
    FROM " . TABLE_PREFIX . "threads t
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = t.fid
    WHERE (
        t.uid = " . (int)$uid . "
        OR FIND_IN_SET(
            '" . $db->escape_string($chara['username']) . "',
            t.charas
        )
    )
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
");

            $threadcount = (int)$db->fetch_field($get_threads, "threadcount");

            if ($postcount == 1) {
                $post = $lang->playeroverview_post;
            } else {
                $post = $lang->playeroverview_posts;
            }

            if ($threadcount == 1) {
                $scene = $lang->playeroverview_scene;
            } else {
                $scene = $lang->playeroverview_scenes;
            }
            $charainplay = $lang->sprintf($lang->playeroverview_inplay, $postcount, $post, $threadcount, $scene);
            $uid = $chara['uid'];

            // Letzten Inplay-Post des Charakters holen
            $get_lastpost = $db->query("
    SELECT 
        p.pid,
        p.dateline,
        t.tid,
        t.subject
    FROM " . TABLE_PREFIX . "posts p
    INNER JOIN " . TABLE_PREFIX . "threads t
        ON t.tid = p.tid
    INNER JOIN " . TABLE_PREFIX . "forums f
        ON f.fid = t.fid
    WHERE p.uid = " . (int)$uid . "
    AND (
        f.parentlist LIKE '" . $inplay_cat . ",%'
        OR CONCAT(',', f.parentlist, ',') LIKE '%," . $archive_forum . ",%'
    )
    ORDER BY p.dateline DESC
    LIMIT 1
");

            if ($lastpost = $db->fetch_array($get_lastpost)) {

                $scenetitle = htmlspecialchars_uni($lastpost['subject']);

                $postdate = my_date(
                    'd.m.Y',
                    $lastpost['dateline']
                );

                $lastinplaypost = $lang->sprintf(
                    $lang->playeroverview_lastscene,
                    $scenetitle,
                    $postdate
                );
            } else {

                $lastinplaypost = $lang->playeroverview_noscene;
            }

            eval("\$charas .= \"" . $templates->get("playeroverview_playerstat_charas") . "\";");
        }



        eval("\$po_js = \"" . $templates->get("playeroverview_javascript") . "\";");
        eval("\$page = \"" . $templates->get("playeroverview_playerstat") . "\";");
        output_page($page);
    }
}
