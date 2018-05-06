<?php

namespace pocketmine\block;


use pocketmine\level\Position;

class BlockFactory
{
    /** @var \SplFixedArray<Block> */
    private static $list = null;
    /** @var \SplFixedArray<Block> */
    private static $fullList = null;

    /** @var \SplFixedArray<bool> */
    public static $solid = null;
    /** @var \SplFixedArray<bool> */
    public static $transparent = null;
    /** @var \SplFixedArray<float> */
    public static $hardness = null;
    /** @var \SplFixedArray<int> */
    public static $light = null;
    /** @var \SplFixedArray<int> */
    public static $lightFilter = null;
    /** @var \SplFixedArray<bool> */
    public static $diffusesSkyLight = null;
    /** @var \SplFixedArray<float> */
    public static $blastResistance = null;

    public static function init(bool $force = false): void{
        if(self::$list === \null or $force){
            self::$list = new \SplFixedArray(256);
            self::$fullList = new \SplFixedArray(4096);

            self::$light = new \SplFixedArray(256);
            self::$lightFilter = new \SplFixedArray(256);
            self::$solid = new \SplFixedArray(256);
            self::$hardness = new \SplFixedArray(256);
            self::$transparent = new \SplFixedArray(256);
            self::$diffusesSkyLight = new \SplFixedArray(256);
            self::$blastResistance = new \SplFixedArray(256);

            self::registerBlock(new Air()); //$list[self::AIR] = Air::class;
            self::registerBlock(new Stone()); //$list[self::STONE] = Stone::class;
            self::registerBlock(new Grass()); //$list[self::GRASS] = Grass::class;
            self::registerBlock(new Dirt()); //$list[self::DIRT] = Dirt::class;
            self::registerBlock(new Cobblestone()); //$list[self::COBBLESTONE] = Cobblestone::class;
            self::registerBlock(new Planks()); //$list[self::PLANKS] = Planks::class;
            self::registerBlock(new Sapling()); //$list[self::SAPLING] = Sapling::class;
            self::registerBlock(new Bedrock()); //$list[self::BEDROCK] = Bedrock::class;
            self::registerBlock(new Water()); //$list[self::WATER] = Water::class;
            self::registerBlock(new StillWater()); //$list[self::STILL_WATER] = StillWater::class;
            self::registerBlock(new Lava()); //$list[self::LAVA] = Lava::class;
            self::registerBlock(new StillLava()); //$list[self::STILL_LAVA] = StillLava::class;
            self::registerBlock(new Sand()); //$list[self::SAND] = Sand::class;
            self::registerBlock(new Gravel()); //$list[self::GRAVEL] = Gravel::class;
            self::registerBlock(new GoldOre()); //$list[self::GOLD_ORE] = GoldOre::class;
            self::registerBlock(new IronOre()); //$list[self::IRON_ORE] = IronOre::class;
            self::registerBlock(new CoalOre()); //$list[self::COAL_ORE] = CoalOre::class;
            self::registerBlock(new Wood()); //$list[self::WOOD] = Wood::class;
            self::registerBlock(new Leaves()); //$list[self::LEAVES] = Leaves::class;
            self::registerBlock(new Sponge()); //$list[self::SPONGE] = Sponge::class;
            self::registerBlock(new Glass()); //$list[self::GLASS] = Glass::class;
            self::registerBlock(new LapisOre()); //$list[self::LAPIS_ORE] = LapisOre::class;
            self::registerBlock(new Lapis()); //$list[self::LAPIS_BLOCK] = Lapis::class;
            self::registerBlock(new Sandstone()); //$list[self::SANDSTONE] = Sandstone::class;
            self::registerBlock(new RedSandstone()); //$list[self::RED_SANDSTONE] = RedSandstone::class;
            self::registerBlock(new RedSandstoneStairs()); //$list[self::RED_SANDSTONE_STAIRS] = RedSandstoneStairs::class;
            self::registerBlock(new Bed()); //$list[self::BED_BLOCK] = Bed::class;
            self::registerBlock(new Cobweb()); //$list[self::COBWEB] = Cobweb::class;
            self::registerBlock(new TallGrass()); //$list[self::TALL_GRASS] = TallGrass::class;
            self::registerBlock(new DeadBush()); //$list[self::DEAD_BUSH] = DeadBush::class;
            self::registerBlock(new Wool()); //$list[self::WOOL] = Wool::class;
            self::registerBlock(new Dandelion()); //$list[self::DANDELION] = Dandelion::class;
            self::registerBlock(new Flower()); //$list[self::RED_FLOWER] = Flower::class;
            self::registerBlock(new BrownMushroom()); //$list[self::BROWN_MUSHROOM] = BrownMushroom::class;
            self::registerBlock(new RedMushroom()); //$list[self::RED_MUSHROOM] = RedMushroom::class;
            self::registerBlock(new Gold()); //$list[self::GOLD_BLOCK] = Gold::class;
            self::registerBlock(new Iron()); //$list[self::IRON_BLOCK] = Iron::class;
            self::registerBlock(new DoubleSlab()); //$list[self::DOUBLE_SLAB] = DoubleSlab::class;
            self::registerBlock(new Slab()); //$list[self::SLAB] = Slab::class;
            self::registerBlock(new RedSandstoneSlab()); //$list[self::RED_SANDSTONE_SLAB] = RedSandstoneSlab::class;
            self::registerBlock(new DoubleRedSandstoneSlab()); //$list[self::DOUBLE_RED_SANDSTONE_SLAB] = DoubleRedSandstoneSlab::class;
            self::registerBlock(new Bricks()); //$list[self::BRICKS_BLOCK] = Bricks::class;
            self::registerBlock(new TNT()); //$list[self::TNT] = TNT::class;
            self::registerBlock(new Bookshelf()); //$list[self::BOOKSHELF] = Bookshelf::class;
            self::registerBlock(new MossStone()); //$list[self::MOSS_STONE] = MossStone::class;
            self::registerBlock(new Obsidian()); //$list[self::OBSIDIAN] = Obsidian::class;
            self::registerBlock(new Torch()); //$list[self::TORCH] = Torch::class;
            self::registerBlock(new Fire()); //$list[self::FIRE] = Fire::class;
            self::registerBlock(new MonsterSpawner()); //$list[self::MONSTER_SPAWNER] = MonsterSpawner::class;
            self::registerBlock(new WoodStairs()); //$list[self::WOOD_STAIRS] = WoodStairs::class;
            self::registerBlock(new EnderChest()); //$list[self::ENDER_CHEST] = EnderChest::class;
            self::registerBlock(new Chest()); //$list[self::CHEST] = Chest::class;

            self::registerBlock(new DiamondOre()); //[self::DIAMOND_ORE] = DiamondOre::class;
            self::registerBlock(new Diamond()); //[self::DIAMOND_BLOCK] = Diamond::class;
            self::registerBlock(new Workbench()); //[self::WORKBENCH] = Workbench::class;
            self::registerBlock(new Wheat()); //[self::WHEAT_BLOCK] = Wheat::class;
            self::registerBlock(new Farmland()); //[self::FARMLAND] = Farmland::class;
            self::registerBlock(new Furnace()); //[self::FURNACE] = Furnace::class;
            self::registerBlock(new BurningFurnace()); //[self::BURNING_FURNACE] = BurningFurnace::class;
            self::registerBlock(new SignPost()); //[self::SIGN_POST] = SignPost::class;
            self::registerBlock(new WoodDoor()); //[self::WOOD_DOOR_BLOCK] = WoodDoor::class;
            self::registerBlock(new SpruceDoor()); //[self::SPRUCE_DOOR_BLOCK] = SpruceDoor::class;
            self::registerBlock(new BirchDoor()); //[self::BIRCH_DOOR_BLOCK] = BirchDoor::class;
            self::registerBlock(new JungleDoor()); //[self::JUNGLE_DOOR_BLOCK] = JungleDoor::class;
            self::registerBlock(new AcaciaDoor()); //[self::ACACIA_DOOR_BLOCK] = AcaciaDoor::class;
            self::registerBlock(new DarkOakDoor()); //[self::DARK_OAK_DOOR_BLOCK] = DarkOakDoor::class;
            self::registerBlock(new Ladder()); //[self::LADDER] = Ladder::class;
            self::registerBlock(new Concrete()); //[self::CONCRETE] = Concrete::class;
            self::registerBlock(new ConcretePowder()); //[self::CONCRETE_POWDER] = ConcretePowder::class;

            self::registerBlock(new GlazedTerracotta(Block::PURPLE_GLAZED_TERRACOTTA, 0, "Purple Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::WHITE_GLAZED_TERRACOTTA, 0, "White Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::ORANGE_GLAZED_TERRACOTTA, 0, "Orange Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::MAGENTA_GLAZED_TERRACOTTA, 0, "Magenta Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::LIGHT_BLUE_GLAZED_TERRACOTTA, 0, "Light Blue Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::YELLOW_GLAZED_TERRACOTTA, 0, "Yellow Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::LIME_GLAZED_TERRACOTTA, 0, "Lime Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::PINK_GLAZED_TERRACOTTA, 0, "Pink Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::GRAY_GLAZED_TERRACOTTA, 0, "Grey Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::SILVER_GLAZED_TERRACOTTA, 0, "Light Grey Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::CYAN_GLAZED_TERRACOTTA, 0, "Cyan Glazed Terracotta"));

            self::registerBlock(new GlazedTerracotta(Block::BLUE_GLAZED_TERRACOTTA, 0, "Blue Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::BROWN_GLAZED_TERRACOTTA, 0, "Brown Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::GREEN_GLAZED_TERRACOTTA, 0, "Green Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::RED_GLAZED_TERRACOTTA, 0, "Red Glazed Terracotta"));
            self::registerBlock(new GlazedTerracotta(Block::BLACK_GLAZED_TERRACOTTA, 0, "Black Glazed Terracotta"));
            self::registerBlock(new CobblestoneStairs()); //[self::COBBLESTONE_STAIRS] = CobblestoneStairs::class;
            self::registerBlock(new WallSign());
            
            self::registerBlock(new IronDoor());
            self::registerBlock(new RedstoneOre());
            self::registerBlock(new GlowingRedstoneOre());

            self::registerBlock(new SnowLayer());
            self::registerBlock(new Ice());
            self::registerBlock(new Snow());
            self::registerBlock(new Cactus());
            self::registerBlock(new Clay());
            self::registerBlock(new Sugarcane());

            self::registerBlock(new Fence());
            self::registerBlock(new Pumpkin());
            self::registerBlock(new Netherrack());
            self::registerBlock(new SoulSand());
            self::registerBlock(new Glowstone());

            self::registerBlock(new LitPumpkin());
            self::registerBlock(new Cake());

            self::registerBlock(new Trapdoor());
            self::registerBlock(new IronTrapdoor());

            self::registerBlock(new StoneBricks());

            self::registerBlock(new BrownMushroomBlock());
            self::registerBlock(new RedMushroomBlock());

            self::registerBlock(new IronBars());
            self::registerBlock(new GlassPane());
            self::registerBlock(new Melon());
            self::registerBlock(new PumpkinStem());
            self::registerBlock(new MelonStem());
            self::registerBlock(new Vine());
            self::registerBlock(new FenceGate());
            self::registerBlock(new BrickStairs());
            self::registerBlock(new StoneBrickStairs());

            self::registerBlock(new Mycelium());
            self::registerBlock(new WaterLily());
            self::registerBlock(new NetherBrick());

            self::registerBlock(new Portal());
            self::registerBlock(new NetherBrickStairs());
            self::registerBlock(new NetherWart());
            self::registerBlock(new EnchantingTable());

            self::registerBlock(new BrewingStand());
            self::registerBlock(new EndPortalFrame());
            self::registerBlock(new EndPortal());
            self::registerBlock(new EndStone());

            self::registerBlock(new EndStoneBricks());
            self::registerBlock(new EndRod());

            self::registerBlock(new Purpur());
            self::registerBlock(new PurpurStairs());

            self::registerBlock(new ChorusFlower());
            self::registerBlock(new ChorusPlant());

            self::registerBlock(new SandstoneStairs());
            self::registerBlock(new EmeraldOre());

            self::registerBlock(new Emerald());
            self::registerBlock(new SpruceWoodStairs());
            self::registerBlock(new BirchWoodStairs());
            self::registerBlock(new JungleWoodStairs());
            self::registerBlock(new Beacon());
            self::registerBlock(new StoneWall());

            self::registerBlock(new FlowerPot());
            self::registerBlock(new Carrot());
            self::registerBlock(new Potato());
            self::registerBlock(new Anvil());

            self::registerBlock(new TrappedChest());
            self::registerBlock(new Redstone());

            self::registerBlock(new Quartz());
            self::registerBlock(new QuartzStairs());
            self::registerBlock(new DoubleWoodSlab());
            self::registerBlock(new WoodSlab());
            self::registerBlock(new StainedClay());

            self::registerBlock(new Leaves2());
            self::registerBlock(new Wood2());
            self::registerBlock(new AcaciaWoodStairs());
            self::registerBlock(new DarkOakWoodStairs());

            self::registerBlock(new SlimeBlock());
            self::registerBlock(new Prismarine());
            self::registerBlock(new SeaLantern());
            self::registerBlock(new HayBale());
            self::registerBlock(new Carpet());
            self::registerBlock(new HardenedClay());
            self::registerBlock(new Coal());

            self::registerBlock(new PackedIce());
            self::registerBlock(new DoublePlant());

            self::registerBlock(new FenceGateSpruce());
            self::registerBlock(new FenceGateBirch());
            self::registerBlock(new FenceGateJungle());
            self::registerBlock(new FenceGateDarkOak());
            self::registerBlock(new FenceGateAcacia());

            self::registerBlock(new GrassPath());

            self::registerBlock(new Podzol());
            self::registerBlock(new Beetroot());
            self::registerBlock(new Stonecutter());
            self::registerBlock(new GlowingObsidian());
            self::registerBlock(new NetherReactor());

            self::registerBlock(new NetherBrickFence());
            self::registerBlock(new PoweredRail());
            self::registerBlock(new Rail());

            self::registerBlock(new WoodenPressurePlate());
            self::registerBlock(new StonePressurePlate());
            self::registerBlock(new LightWeightedPressurePlate());
            self::registerBlock(new HeavyWeightedPressurePlate());
            self::registerBlock(new LitRedstoneLamp());
            self::registerBlock(new RedstoneLamp());
            self::registerBlock(new RedstoneTorch());
            self::registerBlock(new WoodenButton());
            self::registerBlock(new StoneButton());
            self::registerBlock(new Lever());
            self::registerBlock(new DaylightDetector());
            self::registerBlock(new DaylightDetectorInverted());
            self::registerBlock(new Noteblock());
            self::registerBlock(new SkullBlock());
            self::registerBlock(new NetherQuartzOre());
            self::registerBlock(new ActivatorRail());
            self::registerBlock(new CocoaBlock());
            self::registerBlock(new DetectorRail());
            self::registerBlock(new Tripwire());
            self::registerBlock(new TripwireHook());
            self::registerBlock(new ItemFrame());
            self::registerBlock(new Dispenser());
            self::registerBlock(new Dropper());
            self::registerBlock(new PoweredRepeater());
            self::registerBlock(new UnpoweredRepeater());
            self::registerBlock(new Cauldron());
            self::registerBlock(new InvisibleBedrock());
            self::registerBlock(new Hopper());
            self::registerBlock(new DragonEgg());

            foreach(self::$list as $id => $block){
                if($block === null){
                    self::registerBlock(new UnknownBlock($id));
                }
            }
        }

    }

    /**
     * @return \SplFixedArray
     */
    public static function getBlockStatesArray() : \SplFixedArray{
        return self::$fullList;
    }

    /**
     * Returns a new Block instance with the specified ID, meta and position.
     *
     * @param int      $id
     * @param int      $meta
     * @param Position $pos
     *
     * @return Block
     */
    public static function get(int $id, int $meta = 0, Position $pos = null) : Block{
        if($meta < 0 or $meta > 0xf){
            throw new \InvalidArgumentException("Block meta value $meta is out of bounds");
        }

        try{
            if(self::$fullList !== null){
                $block = clone self::$fullList[($id << 4) | $meta];
            }else{
                $block = new UnknownBlock($id, $meta);
            }
        }catch(\RuntimeException $e){
            throw new \InvalidArgumentException("Block ID $id is out of bounds");
        }

        if($pos !== null){
            $block->x = $pos->getFloorX();
            $block->y = $pos->getFloorY();
            $block->z = $pos->getFloorZ();
            $block->level = $pos->level;
        }

        return $block;
    }

    /**
     * Registers a block type into the index. Plugins may use this method to register new block types or override
     * existing ones.
     *
     * NOTE: If you are registering a new block type, you will need to add it to the creative inventory yourself - it
     * will not automatically appear there.
     *
     * @param Block $block
     * @param bool  $override Whether to override existing registrations
     *
     * @throws \RuntimeException if something attempted to override an already-registered block without specifying the
     * $override parameter.
     */
    public static function registerBlock(Block $block, bool $override = \false) : void{
        $id = $block->getId();

        if(!$override and self::isRegistered($id)){
            throw new \RuntimeException("Trying to overwrite an already registered block");
        }

        self::$list[$id] = clone $block;

        for($meta = 0; $meta < 16; ++$meta){
            $variant = clone $block;
            $variant->setDamage($meta);
            self::$fullList[($id << 4) | $meta] = $variant;
        }

        self::$solid[$id] = $block->isSolid();
        self::$transparent[$id] = $block->isTransparent();
        self::$hardness[$id] = $block->getHardness();
        self::$light[$id] = $block->getLightLevel();
        if($block->isSolid()){
            if($block->isTransparent()){
                if($block instanceof Liquid or $block instanceof Ice){
                    self::$lightFilter[$id] = 2;
                }else{
                    self::$lightFilter[$id] = 1;
                }
            }else{
                self::$lightFilter[$id] = 15;
            }
        }else{
            self::$lightFilter[$id] = 1;
        }
        self::$blastResistance[$id] = $block->getResistance();
    }



    /**
     * Returns whether a specified block ID is already registered in the block factory.
     *
     * @param int $id
     * @return bool
     */
    public static function isRegistered(int $id) : bool{
        $b = self::$list[$id];
        return $b !== \null and !($b instanceof UnknownBlock);
    }
}