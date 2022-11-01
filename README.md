<h1>PlayerGrave<img src="assets/images/icon.png" height="64" width="64" align="left"></img></h1><br/>


[![Lint](https://poggit.pmmp.io/ci.shield/BlockMagicDev/PlayerGrave/PlayerGrave)](https://poggit.pmmp.io/ci/BlockMagicDev/PlayerGrave/PlayerGrave)
[![Discord](https://img.shields.io/discord/979551565415346297.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/ApDXa7Tm)

✨ **A plugin for PocketMine-MP that spawn graves of players when they die.**

# Config

```yaml
#Set it to true to show the player's name on the grave
show-memorial-name: true
# set it to true to limit the spawning grave, which means that while the dead player's spawn grave 
# is still alive, it won't spawn another grave when the player dies.
limit-spawn: true
# Set it to true so that graves can despawn
despawn: true
# Despawn Grave Time in Seconds
despawn-time: 40
# Graves spawn in which worlds? if you want graves spawn only in specific worlds, add world names like this format:
#  worlds:
#    - World
#    - End
worlds: []
```
