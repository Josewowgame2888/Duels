# Duels
Minigame Duels [steadfast]

# Commands
- /duels create [customName] [level]
- /duels pos [1/2] [name]
- /duels lobby [name]
- /duels lobbypos [1/2] [name]
- /duels save [name]
- /duels tp [level]

# NPC
- Change the x-y-z-yaw coordinates src/duels/npc/EntityManager.php
```
    private static function getPosition(): array
    {
        return [230.33,53,13.58,93,0];
    }
```
# Get exact position for the npc
- use: /dpos
