<?php

class View
{

    function generate($template_view)
    {
        include 'app/views/'.$template_view;
    }
}