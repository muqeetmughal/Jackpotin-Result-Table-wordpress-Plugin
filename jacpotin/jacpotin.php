<?php
/*
Plugin Name: Fetch Jacpotin Result
Description: Fetch Jacpotin Result in HTML Table and display it inside any post or page
*/



// Register the shortcode

// Shortcode callback function
function fetch_jackpot($atts)
{
    // Fetch the remote HTML
    $url = 'https://jackpotin.com/result.php'; // Replace with your remote URL

    $html = file_get_contents($url);

    // Parse the HTML to extract content inside <div id="mask">
    $dom = new DOMDocument();

    libxml_use_internal_errors(true);
    $dom->loadHTML($html);

    $panelRootDiv = $dom->getElementById('panel');

    $panelDivs = $panelRootDiv->getElementsByTagName('div');

    $final_html = '';

    $final_html .= '<style>
    #jackpot_today_result_table {
        width: 100%;
        border-collapse: collapse;
        background-color: #d1996e;
        font-size : 18px;
        color : white;
        line-height: 1.5;
    }

    .jackpot_today_result_table_th, .jackpot_today_result_table_td {
        padding: 8px;
        text-align: left;
        border: 1px solid #bebebe;
        
    }

  

    
        </style>';

    $final_html .= '<table id="jackpot_today_result_table">';
    $final_html .= '<thead>
                        <tr>
                        <th class="jackpot_today_result_table_th" data-align="left"><strong>NAME</strong></th>
                        <th class="jackpot_today_result_table_th"  data-align="left"><strong>TIME</strong> TABLE</th>
                        <th class="jackpot_today_result_table_th" data-align="left"><strong>1st Prize</strong></th>
                        <th class="jackpot_today_result_table_th" data-align="left"><strong>2nd Prize</strong></th>
                        </tr>
                    </thead>';

    $final_html .= '<tbody>';

    foreach ($panelDivs as $panel) {

        $text = $panel->textContent;
        $exploded_str = explode(")DRAW ", $text);

        $left_str = $exploded_str[0];
        $left_str_explode = explode(" ", $left_str);
        $name = $left_str_explode[11];
        $time = $left_str_explode[12] . " " . $left_str_explode[13];

        $right_str = $exploded_str[1];
        $right_str_explode = explode("3DIGIT ", $right_str);
        $first_prize = explode("/-", $right_str_explode[1])[1];
        $second_prize = explode("/-", $right_str_explode[2])[1];
        // echo '<pre>'; print_r($left_str_explode); echo '</pre>';
        $final_html .= '<tr class="jackpot_today_result_table_tr">
                            <td class="jackpot_today_result_table_td" data-align="left">' . $name . '</td>
                            <td class="jackpot_today_result_table_td" data-align="left">' . $time . '</td>
                            <td class="jackpot_today_result_table_td" data-align="left">' . $first_prize . '</td>
                            <td class="jackpot_today_result_table_td" data-align="left">' . $second_prize . '</td>
                        </tr>';
        // echo $name . " - " . $time . " - " . $first_prize . " - " . $second_prize . "<hr>";
    }

    $final_html .= '</tbody></table>';

    // echo $final_html;

    return $final_html;
}

add_shortcode('fetch_jackpot', 'fetch_jackpot');
