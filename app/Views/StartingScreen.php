<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <script>
    $(document).ready(function () 
    {
        $('#DungeonWikiButton').click(function () 
        {
            $.ajax({
                url: 'fetchNpc',
                method: 'get',
                dataType: 'json',

                success: function (response)
                {
                    console.log("BUTTON INFO RESPONSE: ", response.buttonInfo);

                    $("#DungeonPediaInfo").empty();
                    $("#DungeonPediaButtons").empty();
                    $("#DungeonPediaInfo").html(response.NPCS);
                    
                    var buttonElements = Object.keys(response.buttonInfo).map(key => 
                    {
                        var trimmedName = key.replace(/\s/g, ''); // [key: value]
                        var buttonHTML = 
                            `<button class='SelectionButton m-3' id='${trimmedName}_button' class='d-flex justify-content-center align-items-center' style='width: 120px; height: 120px;position: relative;'onclick='OpenAndClose("${trimmedName}_info_container")'>
                            <img src='${response.buttonInfo[key]}' style='width: 80px; height: 80px;' class='containers_primary rounded'><br>
                            ${key}</button>`;
                        $("#DungeonPediaButtons").append(buttonHTML);
                        return document.getElementById(`${trimmedName}_button`); //basically we're returning the ids of the newly created buttons and placing them into the array
                    });

                    $("#DungeonPediaInfo .SelectionButton").each(function () 
                    {
                        buttonElements.push(this); //then from the DungeonPediaInfo container (container thats holding each individual character panel) we take all selection buttons
                    });

                    console.log("BUTTON ELEMENTS: ", buttonElements);
                    attachButtonListeners(buttonElements); //once we found em, we shove event listeners onto them (music bullshit, couldnt do it like a normal person would so i had to complicate things xd )
                },

                error: function (error) 
                {
                    console.error("Error occurred:", error);
                }
            });
        });
    });

    window.addEventListener('load', () => 
    {       
                
        var buttons = document.querySelectorAll('.SelectionButton');
        attachButtonListeners(buttons);

        LoadingScreen();
        
        document.getElementById('ContinueButton').addEventListener('click', function()
        {
            PlayRandomTrack();
            this.style.visibility = 'hidden';
            LoadingInfo.remove();
            document.getElementById('SelectionMenu').style.visibility = 'visible';
            TipsThing();
        });
    });

    function PlayRandomTrack() 
    {
        var music = new Audio();    

        music.src = getRandomTrack();
        music.volume = 0.5;
        music.play();
        music.removeEventListener('ended', PlayRandomTrack);
        music.addEventListener('ended', PlayRandomTrack);
    }

    function getRandomTrack() 
    {
        var PlayList = 
        [
            '/assets/SFX/Music/Memories.mp3',
            '/assets/SFX/Music/The_Ascension.mp3',
            '/assets/SFX/Music/Midnight_Hour.mp3',
            '/assets/SFX/Music/Remaining_Routine.mp3',
        ];
        return PlayList[Math.floor(Math.random() * PlayList.length)];
    }

    function LoadingScreen()
    {
    const loadinginfo = document.getElementById('LoadingInfo');
    const continueButton = document.getElementById('ContinueButton');

    for (let a = 1; a <= 18; a++) 
        {
            setTimeout(() => 
            {
                if (a === 18) 
                {
                    loadinginfo.innerHTML = 'Loading finished.';
                    continueButton.style.visibility = 'visible';
                } 
                else if (a % 4 === 1) 
                {
                    loadinginfo.innerHTML = '';
                } 
                else 
                {
                    loadinginfo.innerHTML += '. ';
                }
            }, a * 100);
        };
    }   

    function TipsThing()
    {
        var guidesBox = document.getElementById('GuidesItem');
        var mainContainer = document.getElementById('MainContainer');
        var RandomTips = 
        [
            'The dungeons offer both the chance for greatness and an escape from reality itself.<br>Whichever of these gifts you choose to embrace is up to you.',
            'Keep in mind, most foes you meet in the dungeons are as fearsome as they are desperate, seeking only their survival.',
            'The Red Sage, as he is often called, was once nothing more than a simpleton.',
            'It is said that the Sovereign stepped foot in the dungeons half a century ago, but he was never heard from or seen again.',
            'Try dodging attacks instead of blocking them - heavy blows can still damage your equipment and leave your bones rattled.',
            'Healing potions and other miscellaneous items are quite rare - use them sparingly.',
            'Swords, while nimble and precise, lack the blunt force needed to pose a real threat to a heavily armored opponent.',
            'Beastmen, along with other foul creatures, can be found in the upper and middle levels of the dungeon.',
            'Magical attacks and enchantments can bypass both shields and armor.',
        ];

        let currentIndex = 0;
        let currentItem;
        
        guidesBox.classList.add('animate');
        mainContainer.classList.add('animateShadow');

        setInterval(() => 
        {
            currentItem = RandomTips[currentIndex];

            guidesBox.innerHTML = currentItem;

            currentIndex = (currentIndex + 1) % RandomTips.length;
        }, 3000); 
    }

    function BeginGame()
    {
        var blockScreen = document.getElementById('blockScreen');
        blockScreen.style.visibility = 'visible';
        blockScreen.classList.add('animatePullIn');
    }

    function OpenAndClose(id) 
    {   
        var item = document.getElementById(id);

        if (item.classList.contains('animateOpen')) 
        {
            item.classList.remove('animateOpen');
            item.classList.add('animateClose');
            setTimeout(() =>
            {
                item.style.visibility = 'hidden';
                item.style.display = 'none';
            }, 500);
        } 
        else 
        {
            item.classList.remove('animateClose');
            item.classList.add('animateOpen');
            item.style.visibility = 'visible';
            item.style.display = 'block';
            item.style.overflowY = 'auto';
        }
    }
    
    function attachButtonListeners(buttons)
    {
        buttons.forEach(button => 
        {
            button.addEventListener('mouseenter', function () 
            {
                var hoverSound = new Audio('/assets/SFX/BAAAA.mp3');
                hoverSound.volume = 0.1;
                hoverSound.play();
            });
            button.addEventListener('click', function ()
            {
                var audio = new Audio('/assets/SFX/MenuSelection.mp3');
                audio.volume = 0.35;
                audio.play();
            });
        });
    };

    function RedirectTimeout()
    {
        BeginGame();
        setTimeout(function()
        {
            window.location.href = "<?= site_url('EnterOutskirts')?>";
        }, 1500);
    }
    </script>
    <body>
        <div id="MainContainer" class="StuffContainer d-flex justify-content-center align-items-center flex-column">
            <button class="SelectionButton" style="Visibility: hidden;" id="ContinueButton">Continue</button>
            <p class="big_boy_paragraph" id="LoadingInfo"></p>
            <div id="SelectionMenu" style="visibility: hidden;" class="d-flex justify-content-center align-items-center flex-column">
                <p class="big_boy_paragraph" id="NameThing">The Descent</p>
                <button class="SelectionButton" id="HelpButton" onclick="OpenAndClose('HelpScreen')">Help</button>
                <button class="SelectionButton" id="PlayButton" onclick="RedirectTimeout()">Play</button>
                <button class="SelectionButton" id="CreditsButton" onclick="OpenAndClose('CreditsScreen')">Credits</button>
                <button class="SelectionButton" id="DungeonWikiButton" onclick="OpenAndClose('DungeonPedia')">Dungeon-pedia</button><br>
                <p class="big_boy_paragraph" id="GuidesItem"></p>
            </div>
            <div id="blockScreen"></div>
            <div id="CreditsScreen" style="top: 75%; left: 50%; width: 600px; height: 70px;" class="containerSpecial d-flex justify-content-center align-items-center flex-column">Game made by Gimli</div>
            <div id="DungeonPedia" style="transform: translate(-50%, -50%); top: 50%; left: 50%; width: 1400px; height: 750px; visibility: hidden; overflow-y: auto; overflow-x: hidden;" class="containerSpecial d-flex flex-column">
                <button class="SelectionButton m-3" style="width: 50px; height: 50px;" onclick="OpenAndClose('DungeonPedia')">X</button>
                <div id="DungeonPediaButtons" style="width: 95%; height: 95%;">
                </div>
            </div>
            
            <div id="DungeonPediaInfo" style="position: absolute; transform: translate(-50%, -50%); top: 50%; left: 50%; visibility: hidden; width: 1500px; height: 900px; z-index: 5000;" class="d-flex align-items-center justify-content-center row">
            </div>

            <div id="HelpScreen" style="transform: translate(-50%, -50%); top: 50%; left: 50%; width: 1400px; height: 750px; visibility: hidden;" class="containerSpecial d-flex flex-column">
                <button class="SelectionButton m-3" style="width: 50px; height: 50px;" onclick="OpenAndClose('HelpScreen')">X</button>
                <p class="m-4 fs-5 text-start">
                    The Descent is a brutal turn-based survival RPG that plunges you into the heart of an ever-changing, treacherous dungeon. <br>
                    Your ultimate goal is simple but harrowing: survive and descend as deep as possible. <br>
                    Along the way, you must scavenge for weapons, tools, and resources—everything you find is critical, yet nothing is guaranteed to stay with you for long.<br><br>
                    Each descent is a fresh journey, and death is both an end and a beginning. <br>
                    When you fall, you lose everything you’ve gathered: the weapons you forged, the rare items you scavenged, and the hard-won progress you made. <br>
                    Starting anew is not a punishment but an opportunity—to learn, adapt, and forge a new strategy.<br><br>
                    The dungeon is merciless and offers no singular path to victory. Its halls twist and shift with each attempt, and its challenges demand creativity, cunning, and resolve. <br>
                    Will you become a master of crafting, turning the dungeon’s refuse into makeshift weapons? <br>
                    Or will you hoard resources for that perfect moment, unleashing devastating power at a critical time? <br><br>
                    Perhaps you'll rely on stealth, avoiding unnecessary conflict, or become a master tactician, predicting enemy moves in the turn-based combat to always stay one step ahead.<br>
                    Your choices matter, but so does your adaptability. Every run is a test of your ability to think on your feet and make the most of what little the dungeon gives you. <br>
                    The descent is a journey of discovery—not just of the dungeon’s hidden depths, but of your own capabilities as a survivor.<br>
                    Dare to step into the darkness. The dungeon awaits, and its secrets will either consume you or grant you untold power—if you can claim it.
                </p>
            </div>
        </div>
    </body>
</html>

<style>
    ::-webkit-scrollbar 
    {
    width: 5px;
    }
    /* Track */
    ::-webkit-scrollbar-track
    {
    background: #f1f1f1; 
    }
    
    /* Handle */
    ::-webkit-scrollbar-thumb 
    {
    background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover 
    {
    background: #555; 
    }

    @keyframes fadeSlideInOut 
    {
        0% 
        {
            opacity: 0;
            transform: translateX(-50px); 
        }
        20% 
        {
            opacity: 1;
            transform: translateX(0); 
        }
        80% 
        {
            opacity: 1;
            transform: translateX(0); 
        }
        100% 
        {
            opacity: 0;
            transform: translateX(50px); 
        }
    }

    @keyframes pulseBoxShadow 
    {
        0% 
        {
            box-shadow: inset 0 0 200px black;
        }
        50% 
        {
            box-shadow: inset 0 0 230px #0F0F0F;
        }
        100% 
        {
            box-shadow: inset 0 0 200px black;
        }
    }
    
    @keyframes pullIn
    {
        0% 
        {
        width: 0;
        }
        100% 
        {
            width: 100%;
        }
    }

    @keyframes open
    {
        0%
        {
            opacity: 0;
        }
        100%
        {
            opacity: 1;
        }
    }
    @keyframes close
    {
        0%
        {
            opacity: 1;
        }
        100% 
        {
            opacity: 0;
        }
    }

    .animate
    {
        animation: fadeSlideInOut 3s ease-in-out infinite;
    }
    .animateShadow
    {
        animation: pulseBoxShadow 3s ease-in-out infinite;
    }
    .animatePullIn
    {
        animation: pullIn 1s ease-in forwards;
    }
    .animateOpen
    {
        animation: open 0.5s ease-in;
    }
    .animateClose
    {
        animation: close 0.5s ease-out;
    }

    #blockScreen
    {
        position: absolute;
        right: 0;
        z-index: 1000;
        height: 100%;
        width: 0px;
        visibility: hidden;
        background-color: #0F0F0F; 
        box-shadow: inset 0 0 100px black;
    }
    .containerSpecial
    {
        position: absolute;
        transform: translateX(-50%);
        z-index: 900;
        border-radius: 5px;
        border: 1px solid black;
        box-shadow: inset 0 0 20px black;
        font-family: serif;
        text-shadow: 1px 1px 3px black;
        background-color: gray;
        color: white;
        transform-origin: center;
        visibility: hidden;
    }
    #GuidesItem
    {
        font-weight: normal;    
        font-size: 25px;
        text-align: center;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        width: 1300px;
        height: 160px;
    }

    .StuffContainer
    {
        height: 100vh;
        background-color: #242526;
        box-shadow: inset 0 0 200px black;
    }

    .big_boy_paragraph
    {
        text-align: center;
        font-family: serif;
        color: white;
        font-size: 50px;
        font-weight: bold;
        text-shadow: 1px 1px 12px black;
    }

    .SelectionButton
    {
        box-sizing: border-box;
        display: inline-block;
        box-shadow: inset 0 0 20px black;
        margin: 5px;
        border-radius: 5px;
        font-family: serif;
        border: 1px solid black;
        text-shadow: 1px 1px 3px black;
        background-color: gray;
        color: white;
        width: 150px;
        height: 50px;
        position: relative;
        transition: background-color 0.2s ease, transform 0.2s ease, border 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .SelectionButton::after 
    {
    content: '';
    position: absolute;
    bottom: 0; 
    left: 50%; 
    width: 0; 
    height: 3px; 
    background-color: white; 
    transform: translateX(-50%);
    transform-origin: center; 
    transition: width 0.2s ease; 
    }
    
    .SelectionButton:hover::after 
    {
        width: 100%; 
    }

    .SelectionButton:hover
    {
        box-shadow: inset 0 0 50px black;
        background-color: #556B2F;
        transform: scale(1.05);
        border: 2px solid white;
    }

    .information_thing
    {
        font-size: 18px;
        font-family: serif;
        color: white;
        text-shadow: 1px 1px 3px black;
    }
    
    .npc_container
    {
        border: 1px solid white; 
        background-color: #242526; 
        box-shadow: inset 0 0 200px black;
    }

    .colors_primary
    {
        box-shadow: inset 0 0 20px black;
        font-family: serif;
        border: 1px solid black;
        text-shadow: 1px 1px 3px black;
        background-color: gray;
        color: white;
    }
    .containers_primary
    {
        box-shadow: inset 0 0 20px black;
        font-family: serif;
        border: 1px solid white;
        text-shadow: 1px 1px 3px black;
        background-color: #696969;
        color: white;
    }
    .box_effects
    {
        box-shadow: inset 0 0 20px dimgray;
        border: 1px solid black;
    }
</style>