<?php

function remove_unique_constraints($table, $column)
{
    CMS::$MySql->Prepare("
        SELECT INDEX_NAME
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = ?
        AND TABLE_NAME = ?
        AND COLUMN_NAME = ?
        AND NOT NON_UNIQUE
    ");

    CMS::$MySql->Execute([CMS::$MySql->getDatabase(), $table, $column]);
    $indexes = CMS::$MySql->getStatement()->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach($indexes as $index) {
        CMS::$MySql->Query("ALTER TABLE ".$table." DROP INDEX " . $index['INDEX_NAME']);
    }
}

function table_exists($table)
{   
    $columns = ['table_schema', 'table_name', 'table_type'];
    $preparedColumns = implode(',', $columns);
    CMS::$MySql->prepare("select $preparedColumns from information_schema.tables where table_schema = ? and table_name = ? and table_type = ?");
    CMS::$MySql->execute(CMS::$MySql->getDatabase(), $table, 'BASE TABLE');
    return !!CMS::$MySql->Fetch($columns);
}

function set_site_setting($name, $value) {
    CMS::$MySql->Prepare("INSERT INTO `settings`
        (`value`, name)
        VALUES(?, ?)
        ON DUPLICATE KEY UPDATE
        `value` = ?");

    CMS::$MySql->execute([$value, $name, $value]);
}

function get_site_setting($name, $default=null)
{
    CMS::$MySql->Prepare("select `value` from settings where `name` = ?");
    CMS::$MySql->execute([$name]);
    $result = CMS::$MySql->Fetch(['value']);
    return data_get($result, 'value', $default);
}