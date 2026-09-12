<?php
    $num_display = 7;
    $page  = $results->currentPage();
    $last  = $results->lastPage();
    $start = ( ($page - $num_display ) > 0 ) ? $page - $num_display : 1;
    $end   = ( ( $page + $num_display ) < $last ) ? $page + $num_display : $last;

    if($last > 1){
        $html  = '<ul class="pagination pagination-sm pull-right">';
        $class = ( $page == 1 ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="' .$results->url( $page - 1 ) . '">&laquo;</a></li>';

        if ( $start > 1 ) {
            $html .= '<li><a href="'.$results->url(1).'">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ( $i = $start ; $i <= $end; $i++ ) {
            $class = ( $page == $i ) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="'. $results->url($i) . '">' . $i . '</a></li>';
        }

        if ( $end < $last ) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="' .$results->url($last) . '">' . $last . '</a></li>';
        }

        $class = ( $page == $last ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="' . $results->url( $page + 1 ) . '">&raquo;</a></li>';
        $html .= '</ul>';
        echo $html;
    }