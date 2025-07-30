<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'city' => ['required', 'string', 'max:255'],
            'apartment' => ['required', 'string', 'max:255'],
            'consent_personal_data' => ['accepted'],
            'consent_email' => ['accepted'],
            'consent_marketing' => ['nullable', 'boolean'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'name.required' => 'Imię i nazwisko jest wymagane.',
            'first_name.required' => 'Imię jest wymagane.',
            'last_name.required' => 'Nazwisko jest wymagane.',
            'phone.required' => 'Telefon jest wymagany.',
            'city.required' => 'Miasto jest wymagane.',
            'apartment.required' => 'Lokal jest wymagany.',
            'email.required' => 'Adres email jest wymagany.',
            'email.email' => 'Podaj poprawny adres email.',
            'email.unique' => 'Ten adres email jest już zajęty.',
            'phone.max' => 'Telefon może mieć maksymalnie 30 znaków.',
            'city.max' => 'Miasto może mieć maksymalnie 255 znaków.',
            'apartment.max' => 'Lokal może mieć maksymalnie 255 znaków.',
            'consent_personal_data.accepted' => 'Musisz wyrazić zgodę na przetwarzanie danych osobowych.',
            'consent_email.accepted' => 'Musisz wyrazić zgodę na przesyłanie informacji drogą elektroniczną.',
        ])->validate();

        $password = Str::random(12);

        // Pobierz pierwszy wolny nick
        $usedNicknames = User::pluck('nickname')->toArray();
        $allNicknames = [
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
            'Mysticbones', 'Sirois', 'Bethane', 'Miriax', 'XArine', 'Vividmist',
            // Nowe nicki:
            'Krakenator', 'AbyssalNova', 'ShadowSquid', 'InkSpecter', 'Cephalon',
            'Tentaclaw', 'Gloomfin', 'AbyssalWisp', 'Krakenite', 'Noctilux',
            'DeepSpectre', 'Inkphantom', 'Cephadusk', 'Tentacore', 'Gloomshroud',
            'AbyssalShade', 'Krakenborn', 'Noctilith', 'Deepwarden', 'Inkshade',
            'Cephacore', 'Tentacron', 'Gloomveil', 'AbyssalEcho', 'Krakenveil',
            'Noctilume', 'Deepmire', 'Inkshadow', 'Cephadream', 'Tentacrest',
            'Gloomspire', 'AbyssalSoul', 'Krakenmind', 'Noctilume', 'Deepdream',
            'Inkspire', 'Cephawhisper', 'Tentacrest', 'Gloomshade', 'AbyssalWhisper',
            'Krakenwhisper', 'Noctilume', 'Deepwhisper', 'Inkwhisper', 'Cephashade',
            'Tentashade', 'Gloomdream', 'AbyssalDream', 'Krakenphantom', 'Noctidream',
            'Deepphantom', 'Inkphantom', 'Cephaphantom', 'Tentaphantom', 'Gloomphantom',
            'AbyssalPhantom', 'Krakenphantom', 'Noctiphantom', 'Deepnova', 'Inknova',
            'Cephanova', 'Tentanova', 'Gloomnova', 'AbyssalNova', 'Krakenova', 'Noctinova',
            'Deepnova', 'Inkshadow', 'Cephashadow', 'Tentashadow', 'Gloomshadow',
            'AbyssalShadow', 'Krakenshadow', 'Noctishadow', 'Deepgloom', 'Inkgloom',
            'Cephagloom', 'Tentagloom', 'Gloomgloom', 'AbyssalGloom', 'Krakengloom',
            'Noctigloom', 'Deepmist', 'Inkmist', 'Cephamist', 'Tentamist', 'Gloommist',
            'AbyssalMist', 'Krakenmist', 'Noctimist',
            // Jeszcze więcej nicków:
            'AbyssalKnight', 'Krakenlord', 'ShadowCurrent', 'Inkling', 'Cephadusk',
            'Tentaclawer', 'Gloomtide', 'AbyssalWarden', 'Krakenmaw', 'Noctilord',
            'Deeporacle', 'Inksworn', 'Cepharune', 'Tentacaster', 'Gloomspire',
            'AbyssalRune', 'Krakeneye', 'NoctilumeX', 'Deepseeker', 'Inkseer',
            'Cepharider', 'Tentacraze', 'GloomveilX', 'AbyssalFang', 'Krakenjaw',
            'NoctiluxX', 'Deepwatcher', 'InkshadeX', 'Cephaclaw', 'Tentacrush',
            'GloomshadeX', 'AbyssalGaze', 'Krakenfury', 'NoctilumeZ', 'Deepwanderer',
            'InkshadowX', 'CephadreamX', 'TentacrestX', 'GloomspireX', 'AbyssalSoulX',
            'KrakenmindX', 'NoctilumeY', 'DeepdreamX', 'InkspireX', 'CephawhisperX',
            'TentacrestY', 'GloomshadeY', 'AbyssalWhisperX', 'KrakenwhisperX', 'NoctilumeW',
            'DeepwhisperX', 'InkwhisperX', 'CephashadeX', 'TentashadeX', 'GloomdreamX',
            'AbyssalDreamX', 'KrakenphantomX', 'NoctidreamX', 'DeepphantomX', 'InkphantomX',
            'CephaphantomX', 'TentaphantomX', 'GloomphantomX', 'AbyssalPhantomX', 'KrakenphantomY',
            'NoctiphantomX', 'DeepnovaX', 'InknovaX', 'CephanovaX', 'TentanovaX', 'GloomnovaX',
            'AbyssalNovaX', 'KrakenovaX', 'NoctinovaX', 'DeepnovaY', 'InkshadowY', 'CephashadowY',
            'TentashadowY', 'GloomshadowY', 'AbyssalShadowX', 'KrakenshadowX', 'NoctishadowX',
            'DeepgloomX', 'InkgloomX', 'CephagloomX', 'TentagloomX', 'GloomgloomX', 'AbyssalGloomX',
            'KrakengloomX', 'NoctigloomX', 'DeepmistX', 'InkmistX', 'CephamistX', 'TentamistX',
            'GloommistX', 'AbyssalMistX', 'KrakenmistX', 'NoctimistX', 'DeepmistY', 'InkmistY',
            'CephamistY', 'TentamistY', 'GloommistY', 'AbyssalMistY', 'KrakenmistY', 'NoctimistY',
            'AbyssalStorm', 'Krakenwave', 'ShadowTide', 'Inkstorm', 'Cephastorm', 'Tentastorm',
            'Gloomstorm', 'AbyssalTide', 'Krakenstream', 'Noctitide', 'Deepcurrent', 'Inkcurrent',
            'Cephacurrent', 'Tentacurrent', 'Gloomcurrent', 'AbyssalCurrent', 'Krakenflow',
            'Noctiflow', 'Deepflow', 'Inkflow', 'Cephaflow', 'Tentaflow', 'Gloomflow',
            'AbyssalFlow', 'Krakenpulse', 'Noctipulse', 'Deeppulse', 'Inkpulse', 'Cephapulse',
            'Tentapulse', 'Gloompulse', 'AbyssalPulse', 'Krakenflare', 'Noctiflare', 'Deepflare',
            'Inkflare', 'Cephaflare', 'Tentaflare', 'Gloomflare', 'AbyssalFlare', 'Krakenlight',
            'Noctilight', 'Deeplight', 'Inklight', 'Cephalight', 'Tentalight', 'Gloomlight',
            'AbyssalLight', 'Krakenstar', 'Noctistar', 'Deepstar', 'Inkstar', 'Cephastar',
            'Tentastar', 'Gloomstar', 'AbyssalStar', 'Krakenpearl', 'Noctipearl', 'Deeppearl',
            'Inkpearl', 'Cephapearl', 'Tentapearl', 'Gloompearl', 'AbyssalPearl', 'Krakenstone',
            'Noctistone', 'Deepstone', 'Inkstone', 'Cephastone', 'Tentastone', 'Gloomstone',
            'AbyssalStone', 'KrakeneyeX', 'Noctieye', 'Deepeye', 'Inkeye', 'Cephaeye', 'Tentaeye',
            'Gloomeye', 'AbyssalEye', 'Krakenheart', 'Noctiheart', 'Deepheart', 'Inkheart',
            'Cephaheart', 'Tentaheart', 'Gloomheart', 'AbyssalHeart',
        ];
        $nickname = null;
        foreach ($allNicknames as $nick) {
            if (!in_array($nick, $usedNicknames)) {
                $nickname = $nick;
                break;
            }
        }
        if (!$nickname) {
            $nickname = 'User' . (User::max('id') + 1);
        }

        $user = User::create([
            'name' => $input['name'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => $input['phone'],
            'city' => $input['city'],
            'apartment' => $input['apartment'],
            'consent_personal_data' => $input['consent_personal_data'] ?? false,
            'consent_email' => $input['consent_email'] ?? false,
            'consent_marketing' => $input['consent_marketing'] ?? false,
            'email' => $input['email'],
            'password' => Hash::make($password),
            'is_admin' => false,
            'nickname' => $nickname,
        ]);

        Mail::to($user->email)->send(new UserWelcomeMail($user, $password));

        return $user;
    }
}
