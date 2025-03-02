window.onload = function() {
    var config = {
        type: Phaser.AUTO,
        width: 800,
        height: 600,
        parent: 'game-container',
        scene: {
            preload: preload,
            create: create
        }
    };
    var game = new Phaser.Game(config);
    
    function preload() {
        // Ładowanie tła – podmień ścieżkę do assetu lub usuń, jeśli nie masz grafiki
        this.load.image('background', 'assets/background.png');
    }
    
    var questionText, optionA, optionB, optionC;
    var correctAnswer = 'A'; // Dla przykładu poprawna odpowiedź to A
    function create() {
        // Ustawienie tła (jeśli asset jest dostępny)
        // this.add.image(400, 300, 'background');
        
        // Wyświetlenie pytania
        questionText = this.add.text(100, 50, "Ile to jest 2 + 2?", { fontSize: '32px', fill: '#fff' });
        
        // Opcje odpowiedzi
        optionA = this.add.text(100, 150, "A) 4", { fontSize: '28px', fill: '#0f0' }).setInteractive();
        optionB = this.add.text(100, 250, "B) 22", { fontSize: '28px', fill: '#ff0' }).setInteractive();
        optionC = this.add.text(100, 350, "C) 5", { fontSize: '28px', fill: '#f00' }).setInteractive();
        
        optionA.on('pointerdown', () => checkAnswer('A', this));
        optionB.on('pointerdown', () => checkAnswer('B', this));
        optionC.on('pointerdown', () => checkAnswer('C', this));
    }
    
    function checkAnswer(selected, scene) {
        if(selected === correctAnswer) {
            scene.add.text(100, 450, "Dobra odpowiedź! +10 punktów", { fontSize: '32px', fill: '#0f0' });
            // Tutaj możesz wysłać żądanie AJAX do backendu, aby dodać punkty do konta użytkownika
        } else {
            scene.add.text(100, 450, "Zła odpowiedź! Spróbuj ponownie.", { fontSize: '32px', fill: '#f00' });
        }
    }
};
