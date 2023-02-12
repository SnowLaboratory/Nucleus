<?php

function app()
{
    return Nucleus::getInstance(0);
}

function resolve($key)
{
    return app()->resolve($key);
}