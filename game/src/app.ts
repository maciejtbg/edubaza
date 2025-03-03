import Phaser from "phaser";

// Definicje interfejsów i klas pomocniczych
interface Stats {
  strength: number;
  mana: number;
  agility: number;
  life: number;
}

interface Item {
  id: number;
  name: string;
  bonus: Partial<Stats>;
  cost: number;
}

class Monster {
  name: string;
  stats: Stats;

  constructor() {
    this.name = "Potwór";
    this.stats = {
      strength: 8,
      mana: 0,
      agility: 8,
      life: 50,
    };
  }
}

class Player {
  race: string;
  level: number;
  points: number;
  loginStreak: number;
  stats: Stats;
  inventory: Item[];
  equippedItems: Item[];
  monster: Monster;

  constructor() {
    this.race = "Człowiek";
    this.level = 1;
    this.points = 100;
    this.loginStreak = 1;
    this.stats = {
      strength: 10,
      mana: 10,
      agility: 10,
      life: 100,
    };
    this.inventory = [];
    this.equippedItems = [];
    this.monster = new Monster();
  }

  equipItem(item: Item) {
    const index = this.inventory.findIndex((i) => i.id === item.id);
    if (index !== -1 && !this.equippedItems.includes(item)) {
      this.equippedItems.push(item);
      this.updateStats(item.bonus, true);
    }
  }

  unequipItem(item: Item) {
    const index = this.equippedItems.findIndex((i) => i.id === item.id);
    if (index !== -1) {
      this.equippedItems.splice(index, 1);
      this.updateStats(item.bonus, false);
    }
  }

  private updateStats(bonus: Partial<Stats>, isEquipping: boolean) {
    const modifier = isEquipping ? 1 : -1;
    if (bonus.strength) this.stats.strength += modifier * bonus.strength;
    if (bonus.mana) this.stats.mana += modifier * bonus.mana;
    if (bonus.agility) this.stats.agility += modifier * bonus.agility;
    if (bonus.life) this.stats.life += modifier * bonus.life;
  }
}

// Globalny obiekt gracza
const player = new Player();

// Główna scena gry
class MainScene extends Phaser.Scene {
  constructor() {
    super({ key: "MainScene" });
  }

  create() {
    this.add
      .text(400, 50, `Rasa: ${player.race}   Poziom: ${player.level}`, { font: "20px Arial", color: "#ffffff" })
      .setOrigin(0.5);
    this.add
      .text(400, 80, `Punkty: ${player.points}   Loginy: ${player.loginStreak}`, { font: "20px Arial", color: "#ffffff" })
      .setOrigin(0.5);
    this.add
      .text(400, 110, `Siła: ${player.stats.strength}   Mana: ${player.stats.mana}   Zręczność: ${player.stats.agility}   Życie: ${player.stats.life}`, { font: "20px Arial", color: "#ffffff" })
      .setOrigin(0.5);

    const buttons = [
      { text: "Sklep", x: 50, y: 200, scene: "ShopScene" },
      { text: "Ekwipunek", x: 50, y: 250, scene: "InventoryScene" },
      { text: "Walka", x: 650, y: 200, scene: "BattleScene" },
      { text: "Matematyka", x: 650, y: 250, scene: "MathScene" },
    ];

    buttons.forEach((btn) => {
      this.add
        .text(btn.x, btn.y, btn.text, { font: "24px Arial", backgroundColor: "#0000ff", padding: { x: 10, y: 5 } })
        .setInteractive()
        .on("pointerdown", () => this.scene.start(btn.scene));
    });

    const graphics = this.add.graphics();
    graphics.fillStyle(0x00ff00, 1);
    graphics.fillRect(370, 300, 60, 60);
  }
}

// Scena walki
class BattleScene extends Phaser.Scene {
  monster: Monster;
  playerTurn: boolean;

  constructor() {
    super({ key: "BattleScene" });
    this.monster = new Monster();
    this.playerTurn = true;
  }

  create() {
    this.add.text(400, 50, "Walka", { font: "32px Arial", color: "#ffffff" }).setOrigin(0.5);
    this.add.text(100, 100, `Potwór: ${this.monster.name}   Życie: ${this.monster.stats.life}`, { font: "20px Arial", color: "#ff0000" });
    this.add.text(500, 100, `Gracz   Życie: ${player.stats.life}`, { font: "20px Arial", color: "#00ff00" });

    const attackTypes: { type: "direct" | "ranged" | "magic"; text: string; y: number }[] = [
      { type: "direct", text: "Atak bezpośredni", y: 300 },
      { type: "ranged", text: "Atak dystansowy", y: 350 },
      { type: "magic", text: "Atak magiczny", y: 400 },
    ];

    attackTypes.forEach((attack) => {
      this.add
        .text(100, attack.y, attack.text, { font: "20px Arial", backgroundColor: "#888888", padding: { x: 10, y: 5 } })
        .setInteractive()
        .on("pointerdown", () => {
          if (this.playerTurn) this.handlePlayerAttack(attack.type);
        });
    });

    this.add
      .text(50, 50, "Powrót", { font: "24px Arial", backgroundColor: "#0000ff", padding: { x: 10, y: 5 } })
      .setInteractive()
      .on("pointerdown", () => this.scene.start("MainScene"));
  }

  handlePlayerAttack(type: "direct" | "ranged" | "magic") {
    let damage = 0;
    if (type === "direct") {
      damage = player.stats.strength * 2;
    } else if (type === "ranged") {
      damage = player.stats.agility * 1.5;
    } else if (type === "magic") {
      damage = player.stats.mana * 2;
      player.stats.mana = Math.max(0, player.stats.mana - 5);
    }
    this.monster.stats.life -= damage;
    this.add.text(400, 500, `Gracz zadał ${damage} obrażeń`, { font: "20px Arial", color: "#ffffff" }).setOrigin(0.5);

    if (this.monster.stats.life <= 0) {
      this.add.text(400, 550, "Pokonałeś potwora!", { font: "24px Arial", color: "#00ff00" }).setOrigin(0.5);
      return;
    }

    this.playerTurn = false;
    this.time.delayedCall(1000, () => this.monsterAttack());
  }

  monsterAttack() {
    const damage = this.monster.stats.strength * 1.5;
    player.stats.life -= damage;
    this.add.text(400, 520, `Potwór zadał ${damage} obrażeń`, { font: "20px Arial", color: "#ff0000" }).setOrigin(0.5);

    if (player.stats.life <= 0) {
      this.add.text(400, 570, "Przegrałeś walkę!", { font: "24px Arial", color: "#ff0000" }).setOrigin(0.5);
      return;
    }
    this.playerTurn = true;
  }
}

// Konfiguracja gry
const config: Phaser.Types.Core.GameConfig = {
  type: Phaser.AUTO,
  width: 800,
  height: 600,
  scene: [MainScene, BattleScene],
  backgroundColor: "#333333",
};

new Phaser.Game(config);
