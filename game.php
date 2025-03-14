

<?php
session_start();

// Zmockowane dane użytkownika (symulacja danych z bazy)
$userData = [
    "username"       => "Janek123",
    "race"           => "orc",
    "gender"         => "female",
    "level"          => 5,
    "points"         => 120,
    "streakDays"     => 30,
    "characterImage" => "assets/orc_female.png"


];

// Przekształcenie danych PHP w format JSON dla JavaScript
$userJson = json_encode($userData);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Gra RPG</title>
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.60.0/dist/phaser.js"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #222;
        }

        canvas {
            display: block;
        }

        /* Stylowanie dla interfejsu gry */
        #game-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #infoPanel {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: white;
            font-size: 18px;
        }

        #rightPanel,
        #leftPanel {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        #rightPanel {
            right: 10px;
        }

        #leftPanel {
            left: 10px;
        }

        .button {
            width: 150px;
            height: 40px;
            background-color: #333;
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <script>
        // Dane z PHP
        const userData = <?= $userJson ?>;

        const config = {
            type: Phaser.AUTO,
            width: window.innerWidth, // Pełny ekran
            height: window.innerHeight,
            backgroundColor: "#444",
            scene: {
                preload: preload,
                create: create
            }
        };

        let game = new Phaser.Game(config);

        function preload() {
            if (userData.error) return;
            this.load.image("character", userData.characterImage);
        }

        function create() {
            if (userData.error) {
                this.add.text(50, 50, userData.error, {
                    fontSize: "20px",
                    color: "#ff0000"
                });
                return;
            }

            // Panel informacyjny na górze
            let infoText = `Rasa: ${userData.race} | Poziom: ${userData.level} | Punkty: ${userData.points} | Logowań: ${userData.streakDays}`;
            this.add.text(window.innerWidth / 2 - 200, 20, infoText, {
                fontSize: "24px",
                color: "#ffffff"
            });

            // Wyświetlanie postaci
            if (userData.missingFile) {
                let rect = this.add.rectangle(window.innerWidth / 2, window.innerHeight / 2, 200, 200, 0x777777);
                rect.setOrigin(0.5);
                this.add.text(window.innerWidth / 2 - 90, window.innerHeight / 2 - 10, "Brak pliku:", {
                    fontSize: "14px",
                    color: "#ffffff"
                });
                this.add.text(window.innerWidth / 2 - 90, window.innerHeight / 2 + 10, userData.missingFile, {
                    fontSize: "12px",
                    color: "#ffffff"
                });
            } else {
                let charSprite = this.add.image(window.innerWidth / 2, window.innerHeight / 2, "character");
                charSprite.setScale(1.5);
                charSprite.setDisplaySize(200,200);
            }

            // Przyciski po prawej stronie
            let rightButtons = ["Misja", "Pojedynek", "Wyścig"];
            rightButtons.forEach((buttonText, index) => {
                let button = this.add.text(window.innerWidth - 160, window.innerHeight / 2 - 100 + (index * 60), buttonText, {
                    fontSize: "18px",
                    color: "#ffffff",
                    backgroundColor: "#333",
                    padding: {
                        x: 10,
                        y: 5
                    },
                    fixedWidth: 140,
                    fixedHeight: 40
                }).setInteractive();
                button.on('pointerdown', () => {
                    console.log(`${buttonText} kliknięte`);
                });
            });

            // Przyciski po lewej stronie
            let leftButtons = ["Wyposażenie", "Czary", "Sklep"];
            leftButtons.forEach((buttonText, index) => {
                let button = this.add.text(20, window.innerHeight / 2 - 100 + (index * 60), buttonText, {
                    fontSize: "18px",
                    color: "#ffffff",
                    backgroundColor: "#333",
                    padding: {
                        x: 10,
                        y: 5
                    },
                    fixedWidth: 140,
                    fixedHeight: 40
                }).setInteractive();
                button.on('pointerdown', () => {
                    console.log(`${buttonText} kliknięte`);
                });
            });
        }
    </script>
</body>

</html>