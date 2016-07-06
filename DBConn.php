<?php

/* Course: CSCI 566 
 * Section: 3
 * ZID: Z1758154, Z1750104
 * Name: Ashish Kharde, Anshul Pratap Singh
 * Assignment No: 9
 * Due Date: April 10, 2015 at 11:59pm
 * Purpose: PHP script to  define class which creates database connection for given parameters
 */
class Connection{
    const HOST = 'courses';
    const USER = 'z1758154';
    const PASS = '19900425';
    const DB = 'z1758154';
    /*const HOST = 'localhost';
    const USER = 'root';
    const PASS = '';
    const DB = 'z1758154';
     */
    public static function getConnection(){
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $conn = new PDO('mysql:host='.self::HOST.';dbname='.self::DB, self::USER, self::PASS, $options);//"mysql:host="+self::HOST+";dbname="+self::DB
        try{
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        }
        catch(PDOException $ex){
            echo 'ERROR: '.$ex->getMessage();
            return null;
        }
    }
}

?>