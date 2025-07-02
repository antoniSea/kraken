<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignNicknamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nicknames = [
            'ObsidianEcho', 'VelarisUmbra', 'NyxCipher', 'CryptWhisper', 'LucentShade',
            'BlackOmen', 'AbyssWalker', 'NightReaver', 'ShadowWarden', 'NocturnVow',
            'VoidMarshal', 'DuskEmissary', 'TenebrisSoul', 'AbyssWhisper', "Kraken’s Veil",
            'Inkbound', 'Nautilusk', 'Deepwake', 'LeviathanEcho', 'DrownedOracle',
            'MidnightTentacle', 'SirensDepth', 'Voidmariner', 'Abyssaria', 'The Murkmind',
            'Nautheia', 'Vow of the Drowned', 'Echo of the Ink', 'Tenebraqua', 'Sealed Below',
            'Thalassyth', 'Inkveil', 'Tentavox', 'Inkshroud', 'The Eighth Seal', 'Cephalore',
            'SilentTentacle', 'Mirekraken', 'Chtonyca', 'VelvetMire', 'The Mind of Eight',
            'SableSucker', 'Cephor', 'Inktide', 'Tentavia', 'Suckra', 'Noctopus', 'Umbropod',
            'Octvex', 'Nautopus', 'Krakeon', 'Tentael', 'Inkrid', 'Tentrox', 'Cephorix',
            'Noxpod', 'Sublimak', 'Okkra', 'Tentros', 'Inkuul', 'Octher', 'Cephux', 'Kravu',
            'Suckra', 'Inkz', 'Vextus', 'Tentha', 'Ocula', 'Mykrin', 'Glypha', 'Druvok',
            'Nykril', 'Detrance', 'Armafire', 'Mirian', 'Aquadarke', 'Omular', 'Tentrah',
            'Cephalopod', 'DarkInk', 'BlackSab', 'Barbiriane', 'Asienthe', 'Malasger',
            'Firebons', 'Maktique', 'Simbio', 'Blazebat', 'Maktodus', 'Masaret', 'Pocostem',
            'Mysticbones', 'Sirois', 'Bethane', 'Miriax', 'XArine', 'Vividmist'
        ];

        $users = DB::table('users')->orderBy('id')->get();
        foreach ($users as $i => $user) {
            if (isset($nicknames[$i])) {
                DB::table('users')->where('id', $user->id)->update(['nickname' => $nicknames[$i]]);
            }
        }
    }
}
