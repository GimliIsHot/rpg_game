<?php

namespace App\Controllers;

class MainController extends BaseController
{
    public function index()
    {
        return view('StartingScreen');
    }

    public function fetchNpcInfo()
    {
        $npcsModel = new \App\Models\NpcsModel();
        $armorModel = new \App\Models\ArmorModel();
        $abilitiesModel = new \App\Models\AbilitiesModel();
        $weaponsModel = new \App\Models\WeaponsModel();

        $npcs = $npcsModel->findAll();
        $NPCinfo = [];

        if(!empty($npcs))
        {
            foreach($npcs as $npc)
            {
                $armorPieces = $this->convertPostgresArray($npc['armor_pieces']);
                $abilities = $this->convertPostgresArray($npc['abilities']);
                $weapons = $this->convertPostgresArray($npc['weapons']);

                $originalname = $npc['name'];
                $name = str_replace(' ', '', $npc['name']);
                $description = $npc['description'];
                $npcIcon = json_decode($npc['image'], true);
                $npcIcon = base_url($npcIcon['icon']);
                log_message('debug', 'NPC Image Path: ' . $npcIcon);
                $health = $npc['health'];

                $armorPieces = $armorModel->whereIn('id', $armorPieces)->findAll(); 
                $weapons = $weaponsModel->whereIn('id', $weapons)->findAll();
                $abilities = $abilitiesModel->whereIn('id', $abilities)->findAll();

                $armorPieces = implode("", $this->createHtmlElements($armorPieces, $name, 'armorPieces'));
                $weapons = implode("", $this->createHtmlElements($weapons, $name, 'weaponPieces'));
                $abilities = implode("", $this->createHtmlElements($abilities, $name, 'abilities'));


                //ROW CLASS ARRANGES CHILD ELEMENTS IN A FLEX CONTAINER - IF THE COMBINED WIDTH OF THE CHILDREN EXCEEDS THE PARENT THEN PAGE BREAK IS INSERTED
                $NPCinfo[] = 
                "
                    <div id='{$name}_info_container' class='npc_container m-3 d-flex flex-column align-items-center rounded' style='position: absolute;'>
                        <div id='{$name}_basic_container' class='row mt-3 rounded colors_primary' style='width: 92.5%; height: auto;'>
                        
                            <div id='{$name}_info' class='m-3 d-flex flex-column align-items-start rounded containers_primary' style='width: 40%; height: 500px; overflow-y:auto;'>
                                <img src='{$npcIcon}' alt='{$npc['name']}' style='width:250px; height: 250px'class='rounded box_effects mx-3 mt-3'><br>
                                <p id='{$name}_name' class='m-3 information_thing text-start'>Name: {$originalname}</p>
                                <p id='{$name}_description' class='m-3 information_thing text-start'>Description: {$description}</p>
                                <p id='{$name}_health'class='m-3 information_thing text-start'><strong>Health: </strong>{$health}</p>
                            </div>

                            <div id='{$name}_armor_pieces_container' class='d-flex flex-column align-items-center justify-content-start rounded containers_primary m-3' style='width: 26%; height: 500px; overflow-y:auto; overflow-x:hidden;'>
                                <p class='fs-1 m-3 text-center'><strong>Armor Pieces</strong></p>
                                <div class='d-flex justify-content-center align-items-center flex-wrap m-3'>
                                    {$armorPieces}
                                </div>
                            </div>

                            <div id='{$name}_weapons_container' class='d-flex flex-column align-items-center justify-content-start rounded containers_primary m-3' style='width: 26%; height: 500px; overflow-y:auto; overflow-x:hidden;'>
                                <p class='fs-1 m-3 text-center'><strong>Weapons</strong></p>
                                <div class='d-flex justify-content-center align-items-center flex-wrap m-3'>
                                    {$weapons}
                                </div>
                            </div>

                        </div>
                        <div id='{$name}_abilities_container' class='d-flex flex-column align-items-start rounded colors_primary m-3' style='width: 92.5%; height: auto; overflow-y:auto;'>
                            <p class='fs-1 m-3 text-center'><strong>Abilities</strong></p>
                            <div class='d-flex justify-content-center align-items-center flex-wrap m-3'>
                                {$abilities}
                            </div>
                        </div>
                    </div>
                ";
            }
            return $this->response->setJSON([
                'error' => false,
                'NPCS' => $NPCinfo,
                'message' => 'YAY NPCS!'
            ]);
        }

        else
        {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'No NPCS :('
            ]);
        }
    }

    private function createHtmlElements($rows, $nameOfNpc, $name)
    {
        $elements = [];

        foreach ($rows as $index => $row)
        {
            $htmlString = "<div id='{$nameOfNpc}_{$name}_row_{$index}' class='rounded d-flex flex-column align-items-start bg-dark box_effects mx-2 mb-3 fs-1' style='overflow-y: scroll; width: 275px; height: 300px;'>";

            foreach($row as $columnName => $value)//$row is basically an array and $column name is the key and $value is the value: $row = ["key1": "value1", "key2": "value2"]
            {
                if($columnName === 'id')
                {
                    continue;
                }
                if($columnName === 'image')
                {
                    $srcArray = json_decode($value, true);
                    $src = $srcArray['icon'];
                    $htmlString .= "<img src='{$src}' alt='{$srcArray['icon']}' class='m-3'>";
                    continue;
                }
                $htmlString .= "<p class='mx-3 information_thing text-start'>" . ucfirst($columnName). ": {$value}</p>";
            }

            $htmlString .= "</div>";
            $elements[] = $htmlString; 
        }
        return $elements;
    }

    private function convertPostgresArray($postgresArray)
    {
        $array = explode(',', trim($postgresArray, '{}'));

        return array_map('intval', $array);
    }
}
