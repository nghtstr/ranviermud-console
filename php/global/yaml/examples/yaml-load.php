<?php

#
#    S P Y C
#      a simple php yaml class
#
# license: [MIT License, http://www.opensource.org/licenses/mit-license.php]
#

include('../spyc.php');

$array = Spyc::YAMLLoad('/home/nghtstr/MUDD/ranviermud/entities/areas/test/rooms.yml');

print_r($array);

