<?php
/*
################################################################################
#                              Bad Code Search                                 #
################################################################################
# Class Name: BadCodeScan                                                      #
# File-Release-Date:  2015/07/15                                               #
#==============================================================================#
# Author: Max Stemplevski                                                      #
# Site:                                                                        #
# Twitter: @stemax                                                             #
# Copyright 2014 - All Rights Reserved.                                        #
################################################################################
*/
/* Licence
 * #############################################################################
 * | This program is free software; you can redistribute it and/or             |
 * | modify it under the terms of the GNU General var License                  |
 * | as published by the Free Software Foundation; either version 2            |
 * | of the License, or (at your option) any later version.                    |
 * |                                                                           |
 * | This program is distributed in the hope that it will be useful,           |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the              |
 * | GNU General var License for more details.                                 |
 * |                                                                           |
 * +---------------------------------------------------------------------------+
 */

class BadCodeScan {

    private $potentially_injected_files = array();
    private $count_bad_files = 0;
    public $path = null;
    public $mask = null;
    public $ext = null;
    public $ver = '1.0';

    public function searchBadCode($path = '.', $mask = 'eval(base64_decode', $ext = '*.php')
    {
        $this->path = $path;
        $this->mask = $mask;
        $this->ext = $ext;

        $directory_files = scandir( $path );
        foreach ($directory_files as $file) {
            if (is_dir($path . '/' . $file))
            {
                if ($file != '.' && $file != '..')
                {
                    $this->path = $path.'/'.$file;
                    $this->searchBadCode($this->path, $mask, $ext);
                }
            }
            if ($file != '.' && $file != '..' || $file == $path)
                foreach (glob($path . '/' . $file."/".$this->ext) as $filename) {
                    if(strstr(file_get_contents($filename), $this->mask) != false)
                    {
                        if ($filename == $path .'/'.$file.'/'.basename(__FILE__)) continue;
                        $this->count_bad_files++;
                        $stat = stat($filename);
                        $this->potentially_injected_files [ $this->count_bad_files ] = new stdClass();
                        $this->potentially_injected_files [ $this->count_bad_files ] -> file = $filename;
                        $this->potentially_injected_files [ $this->count_bad_files ] -> filesize = filesize($filename);
                        $this->potentially_injected_files [ $this->count_bad_files ] -> adate = date('Y-m-d H:i:s',$stat['atime']);
                        $this->potentially_injected_files [ $this->count_bad_files ] -> mdate = date('Y-m-d H:i:s',$stat['mtime']);
                        $this->potentially_injected_files [ $this->count_bad_files ] -> ctime = date('Y-m-d H:i:s',$stat['ctime']);
                        $this->potentially_injected_files [ $this->count_bad_files ] -> uid = $stat['uid'];
                    }
                }
        }
    }

    public function getInjectedFiles()
    {
        return $this->potentially_injected_files;
    }

    public function getCountOfInjectedFiles()
    {
        return $this->count_bad_files;
    }

    public function showResultTable()
    {
        $injected_files = $this->getInjectedFiles();
        echo '<h3> Search injected files by mask "'.$this->mask.'". V '.$this->ver.'</h3>';
        echo '<table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid black; font-size:12px;">';
        echo '<tr><td><b>File</b></td><td><b>Size(b)</b></td><td><b>Created date</b></td><td><b>Modificate date</b></td><td><b>Last access</b></td><td><b>User id</b></td><td><b>Status</b></td></tr>';
        if (sizeof($injected_files))
            foreach($injected_files as $injected_file)
            {
                echo "<tr><td>".$injected_file->file."</td><td>" .$injected_file->filesize."</td><td>".$injected_file->ctime."</td><td>".$injected_file->mdate."</td><td>".$injected_file->adate."</td><td>".$injected_file->uid."</td>";
                echo '<td><font color="red">FILE IS POTENTIALLY INJECTED!</font></td>';
                echo '</tr>';
            }
        echo '</table>';
        echo '<h5>Found:'.($this->getCountOfInjectedFiles()).' file(s).</h5>';
    }

}