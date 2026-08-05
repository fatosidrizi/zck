<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Event;
use App\Models\News;
use App\Models\Ngo;
use App\Models\PublicCall;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Communities ──
        $communities = [
            ['name' => ['en' => 'Serbian', 'sq' => 'Serbe'], 'description' => ['en' => 'The Serbian community is one of the largest minority communities in Kosovo, with a rich cultural heritage, language, and traditions preserved across generations.', 'sq' => 'Komuniteti serb është një nga komunitetet pakicë më të mëdha në Kosovë, me një trashëgimi të pasur kulturore, gjuhë dhe tradita të ruajtura ndër breza.'], 'region' => 'Central & Northern Kosovo', 'population' => '~120,000'],
            ['name' => ['en' => 'Turkish', 'sq' => 'Turke'], 'description' => ['en' => 'The Turkish community in Kosovo has a long-standing presence with vibrant cultural traditions, educational institutions, and active civic participation.', 'sq' => 'Komuniteti turk në Kosovë ka një prani të gjatë me tradita kulturore të gjalla, institucione arsimore dhe pjesëmarrje aktive qytetare.'], 'region' => 'Prizren, Prishtina, Gjilan', 'population' => '~18,000'],
            ['name' => ['en' => 'Bosniak', 'sq' => 'Boshnjake'], 'description' => ['en' => 'The Bosniak community maintains a strong cultural identity in Kosovo, particularly in the southern regions, contributing to the diverse social fabric of the country.', 'sq' => 'Komuniteti boshnjak ruan një identitet të fortë kulturor në Kosovë, veçanërisht në rajonet jugore, duke kontribuar në pëlhurën e larmishme shoqërore të vendit.'], 'region' => 'Prizren, Dragash, Peja', 'population' => '~27,000'],
            ['name' => ['en' => 'Roma', 'sq' => 'Rome'], 'description' => ['en' => 'The Roma community in Kosovo faces unique challenges but maintains a vibrant cultural identity. Various programs support their integration and empowerment.', 'sq' => 'Komuniteti rom në Kosovë përballet me sfida unike por ruan një identitet kulturor të gjallë. Programe të ndryshme mbështesin integrimin dhe fuqizimin e tyre.'], 'region' => 'Throughout Kosovo', 'population' => '~8,000'],
            ['name' => ['en' => 'Ashkali', 'sq' => 'Ashkali'], 'description' => ['en' => 'The Ashkali community is an integral part of Kosovo society, with ongoing efforts to improve education, employment, and social inclusion.', 'sq' => 'Komuniteti ashkali është pjesë integrale e shoqërisë kosovare, me përpjekje të vazhdueshme për përmirësimin e arsimit, punësimit dhe përfshirjes sociale.'], 'region' => 'Central Kosovo', 'population' => '~15,000'],
            ['name' => ['en' => 'Egyptian', 'sq' => 'Egjiptiane'], 'description' => ['en' => 'The Egyptian community in Kosovo contributes to the cultural diversity of the country, with active community organizations working on education and development.', 'sq' => 'Komuniteti egjiptian në Kosovë kontribon në diversitetin kulturor të vendit, me organizata aktive komunitare që punojnë në arsim dhe zhvillim.'], 'region' => 'Various municipalities', 'population' => '~11,000'],
            ['name' => ['en' => 'Gorani', 'sq' => 'Gorane'], 'description' => ['en' => 'The Gorani community resides primarily in the Dragash municipality, preserving unique cultural traditions, language, and way of life in the mountainous region.', 'sq' => 'Komuniteti goran banon kryesisht në komunën e Dragashit, duke ruajtur traditat unike kulturore, gjuhën dhe mënyrën e jetesës në rajonin malor.'], 'region' => 'Dragash', 'population' => '~10,000'],
            ['name' => ['en' => 'Montenegrin', 'sq' => 'Malazeze'], 'description' => ['en' => 'The Montenegrin community in Kosovo, though small in number, maintains its cultural identity and participates actively in local governance.', 'sq' => 'Komuniteti malazez në Kosovë, edhe pse i vogël në numër, ruan identitetin e tij kulturor dhe merr pjesë aktivisht në qeverisjen lokale.'], 'region' => 'Peja, Istog', 'population' => '~2,000'],
            ['name' => ['en' => 'Croatian', 'sq' => 'Kroate'], 'description' => ['en' => 'The Croatian community in Kosovo preserves its Catholic heritage and cultural traditions, primarily in the Janjevë and Letnicë areas.', 'sq' => 'Komuniteti kroat në Kosovë ruan trashëgiminë e tij katolike dhe traditat kulturore, kryesisht në zonat e Janjevës dhe Letnicës.'], 'region' => 'Janjevë, Letnicë', 'population' => '~300'],
        ];

        foreach ($communities as $data) {
            Community::create(array_merge($data, ['slug' => Str::slug($data['name']['en'])]));
        }

        // ── NGOs ──
        $ngos = [
            ['name' => ['en' => 'Voice of Roma, Ashkali and Egyptians', 'sq' => 'Zëri i Romëve, Ashkalive dhe Egjiptianëve'], 'description' => ['en' => 'An organization dedicated to protecting the rights and improving the living conditions of Roma, Ashkali, and Egyptian communities through advocacy, education, and social programs.', 'sq' => 'Një organizatë e përkushtuar për mbrojtjen e të drejtave dhe përmirësimin e kushteve të jetesës së komuniteteve rome, ashkali dhe egjiptiane përmes avokimit, arsimit dhe programeve sociale.'], 'location' => 'Prishtina', 'category' => 'Human Rights', 'contact_email' => 'info@vorae.org', 'contact_phone' => '+383 38 123 456'],
            ['name' => ['en' => 'Balkan Sunflowers', 'sq' => 'Luledielli i Ballkanit'], 'description' => ['en' => 'A community development organization fostering volunteering, civic engagement, and intercultural dialogue across Kosovo communities.', 'sq' => 'Një organizatë e zhvillimit komunitar që nxit vullnetarizmin, angazhimin qytetar dhe dialogun ndërkulturor në komunitetet e Kosovës.'], 'location' => 'Prishtina', 'category' => 'Youth', 'contact_email' => 'info@balkansunflowers.org', 'contact_phone' => '+383 38 234 567'],
            ['name' => ['en' => 'Community Building Mitrovica', 'sq' => 'Ndërtimi i Komunitetit Mitrovicë'], 'description' => ['en' => 'Working to build bridges between divided communities in Mitrovica through cultural activities, education programs, and cross-community initiatives.', 'sq' => 'Punon për ndërtimin e urave mes komuniteteve të ndara në Mitrovicë përmes aktiviteteve kulturore, programeve arsimore dhe iniciativave ndërkomuniare.'], 'location' => 'Mitrovica', 'category' => 'Anti-Discrimination', 'contact_email' => 'info@cbmitrovica.org', 'contact_phone' => '+383 28 345 678'],
            ['name' => ['en' => 'Kosovo Education Center', 'sq' => 'Qendra Kosovare për Arsim'], 'description' => ['en' => 'Promoting quality education for all communities, with special focus on minority language education and inclusive schooling policies.', 'sq' => 'Promovimi i arsimit cilësor për të gjitha komunitetet, me fokus të veçantë në arsimin në gjuhët e pakicave dhe politikat e shkollimit gjithëpërfshirës.'], 'location' => 'Prishtina', 'category' => 'Education', 'contact_email' => 'info@kec-ks.org', 'contact_phone' => '+383 38 456 789'],
            ['name' => ['en' => 'Initiative for Rights and Equality', 'sq' => 'Iniciativa për të Drejta dhe Barazi'], 'description' => ['en' => 'A legal aid organization providing free legal assistance to victims of discrimination and human rights violations across Kosovo.', 'sq' => 'Një organizatë e ndihmës juridike që ofron ndihmë juridike falas për viktimat e diskriminimit dhe shkeljeve të të drejtave të njeriut në Kosovë.'], 'location' => 'Prizren', 'category' => 'Legal Aid', 'contact_email' => 'info@ire-ks.org', 'contact_phone' => '+383 29 567 890'],
            ['name' => ['en' => 'Youth Initiative for Human Rights', 'sq' => 'Iniciativa Rinore për të Drejtat e Njeriut'], 'description' => ['en' => 'Empowering young people from all communities to become active citizens and advocates for human rights and interethnic dialogue.', 'sq' => 'Fuqizimi i të rinjve nga të gjitha komunitetet për t\'u bërë qytetarë aktivë dhe avokatë për të drejtat e njeriut dhe dialogun ndëretnik.'], 'location' => 'Prishtina', 'category' => 'Youth', 'contact_email' => 'info@yihr-ks.org', 'contact_phone' => '+383 38 678 901'],
        ];

        foreach ($ngos as $data) {
            Ngo::create(array_merge($data, [
                'slug' => Str::slug($data['name']['en']),
                'is_active' => true,
            ]));
        }

        // ── News ──
        $news = [
            [
                'title' => ['en' => 'New Strategy for Community Rights Protection 2026-2030 Approved', 'sq' => 'Strategjia e Re për Mbrojtjen e të Drejtave të Komuniteteve 2026-2030 u Miratua'],
                'body' => ['en' => '<p>The Government of Kosovo has approved the new Strategy for the Protection and Promotion of the Rights of Communities and their Members for the period 2026-2030.</p><p>This strategy outlines key priorities including:</p><ul><li>Strengthening legal protection mechanisms for minority communities</li><li>Improving access to public services in community languages</li><li>Enhancing community participation in decision-making processes</li><li>Supporting cultural preservation and heritage programs</li></ul><p>The Office for Community Issues will coordinate the implementation across all government institutions.</p>', 'sq' => '<p>Qeveria e Kosovës ka miratuar Strategjinë e re për Mbrojtjen dhe Promovimin e të Drejtave të Komuniteteve dhe Anëtarëve të tyre për periudhën 2026-2030.</p><p>Kjo strategji përshkruan prioritetet kryesore duke përfshirë:</p><ul><li>Forcimin e mekanizmave të mbrojtjes ligjore për komunitetet pakicë</li><li>Përmirësimin e qasjes në shërbimet publike në gjuhët e komuniteteve</li><li>Rritjen e pjesëmarrjes së komuniteteve në proceset vendimmarrëse</li><li>Mbështetjen e programeve për ruajtjen e kulturës dhe trashëgimisë</li></ul><p>Zyra për Çështje të Komuniteteve do të koordinojë zbatimin në të gjitha institucionet qeveritare.</p>'],
                'category' => 'news',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => ['en' => 'Community Dialogue Forum Held in Prizren', 'sq' => 'Forumi i Dialogut Komunitar u Mbajt në Prizren'],
                'body' => ['en' => '<p>The Office for Community Issues organized a Community Dialogue Forum in Prizren, bringing together representatives from all communities in the municipality.</p><p>Over 150 participants discussed challenges related to education, employment, and cultural preservation. Key outcomes include agreements on joint cultural events and a new working group for language rights monitoring.</p><p>The forum is part of a series of regional dialogues planned across Kosovo throughout 2026.</p>', 'sq' => '<p>Zyra për Çështje të Komuniteteve organizoi një Forum të Dialogut Komunitar në Prizren, duke bashkuar përfaqësues nga të gjitha komunitetet në komunë.</p><p>Mbi 150 pjesëmarrës diskutuan sfida lidhur me arsimin, punësimin dhe ruajtjen e kulturës. Rezultatet kryesore përfshijnë marrëveshje për ngjarje të përbashkëta kulturore dhe një grup të ri pune për monitorimin e të drejtave gjuhësore.</p><p>Forumi është pjesë e një serie dialogësh rajonalë të planifikuara në Kosovë gjatë vitit 2026.</p>'],
                'category' => 'news',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => ['en' => 'Quarterly Bulletin: Community Rights Monitoring Report Q2 2026', 'sq' => 'Buletini Tremujor: Raporti i Monitorimit të të Drejtave të Komuniteteve T2 2026'],
                'body' => ['en' => '<p>The Office for Community Issues has published its quarterly monitoring report covering April-June 2026.</p><p>Key findings include improvements in multilingual signage compliance and increased community representation in municipal assemblies. The report also identifies areas requiring attention, including language rights in healthcare settings.</p>', 'sq' => '<p>Zyra për Çështje të Komuniteteve ka publikuar raportin e saj tremujor të monitorimit që mbulon periudhën prill-qershor 2026.</p><p>Gjetjet kryesore përfshijnë përmirësime në pajtueshmërinë e sinjalistikës shumëgjuhëshe dhe rritje të përfaqësimit të komuniteteve në kuvendet komunale. Raporti gjithashtu identifikon fusha që kërkojnë vëmendje, duke përfshirë të drejtat gjuhësore në mjediset shëndetësore.</p>'],
                'category' => 'bulletin',
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => ['en' => 'Annual Report on Discrimination Cases in Kosovo 2025', 'sq' => 'Raporti Vjetor mbi Rastet e Diskriminimit në Kosovë 2025'],
                'body' => ['en' => '<p>The annual report on discrimination cases received through the ZCK platform has been published. In 2025, the office processed 247 reports of discrimination, with the majority related to language-based and ethnic discrimination.</p><p>67% of cases were resolved through mediation, 18% were referred to legal proceedings, and 15% are still under review. The report includes recommendations for legislative improvements.</p>', 'sq' => '<p>Raporti vjetor mbi rastet e diskriminimit të marra përmes platformës ZCK është publikuar. Në vitin 2025, zyra përpunoi 247 raporte të diskriminimit, me shumicën e lidhur me diskriminimin e bazuar në gjuhë dhe etnicitet.</p><p>67% e rasteve u zgjidhën përmes ndërmjetësimit, 18% u referuan në procedura ligjore, dhe 15% janë ende nën shqyrtim. Raporti përfshin rekomandime për përmirësime legjislative.</p>'],
                'category' => 'report',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => ['en' => 'International Day of Tolerance Celebrated Across Kosovo', 'sq' => 'Dita Ndërkombëtare e Tolerancës u Festua në Kosovë'],
                'body' => ['en' => '<p>Communities across Kosovo came together to celebrate the International Day of Tolerance with a series of cultural events, exhibitions, and educational workshops.</p><p>Events were held in Prishtina, Prizren, Mitrovica, and Gjilan, featuring traditional music, food, and art from all communities. The celebrations highlighted Kosovo\'s commitment to diversity and mutual respect.</p>', 'sq' => '<p>Komunitetet në Kosovë u bashkuan për të festuar Ditën Ndërkombëtare të Tolerancës me një seri ngjarjesh kulturore, ekspozitash dhe punëtorive arsimore.</p><p>Ngjarjet u mbajtën në Prishtinë, Prizren, Mitrovicë dhe Gjilan, duke paraqitur muzikë tradicionale, ushqim dhe art nga të gjitha komunitetet. Festimet theksuan përkushtimin e Kosovës ndaj diversitetit dhe respektit reciprok.</p>'],
                'category' => 'news',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => ['en' => 'Training Program for Community Mediators Launched', 'sq' => 'Programi i Trajnimit për Ndërmjetësuesit e Komuniteteve u Lansua'],
                'body' => ['en' => '<p>A new training program for community mediators has been launched in partnership with the OSCE Mission in Kosovo. The program will train 60 mediators from all communities over the next 12 months.</p><p>Mediators will be equipped to handle intercommunity disputes, facilitate dialogue, and support conflict resolution at the local level.</p>', 'sq' => '<p>Një program i ri trajnimi për ndërmjetësuesit e komuniteteve është lansuar në partneritet me Misionin e OSBE-së në Kosovë. Programi do të trajnojë 60 ndërmjetësues nga të gjitha komunitetet gjatë 12 muajve të ardhshëm.</p><p>Ndërmjetësuesit do të pajisen për të trajtuar mosmarrëveshjet ndërkomuniare, lehtësuar dialogun dhe mbështetur zgjidhjen e konflikteve në nivel lokal.</p>'],
                'category' => 'news',
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($news as $data) {
            News::create(array_merge($data, [
                'slug' => Str::slug($data['title']['en']),
                'status' => 'published',
                'author_id' => 1,
            ]));
        }

        // ── Public Calls ──
        $calls = [
            [
                'title' => ['en' => 'Grant Program for Community Cultural Projects 2026', 'sq' => 'Programi i Granteve për Projektet Kulturore Komunitare 2026'],
                'body' => ['en' => '<p>The Office for Community Issues announces a grant program for NGOs working on cultural preservation and intercultural dialogue projects.</p><h3>Eligibility</h3><ul><li>Registered NGOs serving minority communities</li><li>Projects promoting cultural heritage preservation</li><li>Budget requests between 5,000 - 25,000 EUR</li></ul><h3>How to Apply</h3><p>Submit your project proposal through the platform registration system. Include a detailed budget, timeline, and expected outcomes.</p>', 'sq' => '<p>Zyra për Çështje të Komuniteteve shpall programin e granteve për OJQ-të që punojnë në projekte të ruajtjes kulturore dhe dialogut ndërkulturor.</p><h3>Kriteret</h3><ul><li>OJQ-të e regjistruara që shërbejnë komunitetet pakicë</li><li>Projektet që promovojnë ruajtjen e trashëgimisë kulturore</li><li>Kërkesat buxhetore mes 5,000 - 25,000 EUR</li></ul><h3>Si të Aplikoni</h3><p>Dorëzoni propozimin tuaj të projektit përmes sistemit të regjistrimit të platformës. Përfshini një buxhet të detajuar, afat kohor dhe rezultate të pritura.</p>'],
                'type' => 'grant',
                'deadline' => now()->addDays(45),
            ],
            [
                'title' => ['en' => 'Open Position: Community Relations Officer - Mitrovica Region', 'sq' => 'Pozitë e Hapur: Zyrtar për Marrëdhënie me Komunitetet - Rajoni i Mitrovicës'],
                'body' => ['en' => '<p>The Office for Community Issues is seeking a qualified Community Relations Officer for the Mitrovica region.</p><h3>Requirements</h3><ul><li>University degree in social sciences, law, or related field</li><li>Minimum 3 years experience in community development</li><li>Fluency in Albanian and Serbian; English is an advantage</li><li>Strong communication and mediation skills</li></ul><p>Salary range: Grade 7 of the Kosovo Civil Service pay scale.</p>', 'sq' => '<p>Zyra për Çështje të Komuniteteve kërkon një Zyrtar të kualifikuar për Marrëdhënie me Komunitetet për rajonin e Mitrovicës.</p><h3>Kërkesat</h3><ul><li>Diploma universitare në shkencat shoqërore, drejtësi ose fushë të ngjashme</li><li>Minimum 3 vjet përvojë në zhvillimin komunitar</li><li>Njohja rrjedhshme e gjuhës shqipe dhe serbe; anglishtja është përparësi</li><li>Aftësi të forta komunikimi dhe ndërmjetësimi</li></ul><p>Shkalla e pagës: Grada 7 e shkallës së pagave të Shërbimit Civil të Kosovës.</p>'],
                'type' => 'recruitment',
                'deadline' => now()->addDays(20),
            ],
            [
                'title' => ['en' => 'Call for Applications: Community Media Support Fund', 'sq' => 'Thirrje për Aplikime: Fondi i Mbështetjes së Medias Komunitare'],
                'body' => ['en' => '<p>Applications are now open for the Community Media Support Fund, aimed at strengthening media outlets serving minority communities in Kosovo.</p><p>Eligible applicants include community radio stations, online news portals, and print media in minority languages. Grants of up to 15,000 EUR are available.</p>', 'sq' => '<p>Aplikimet tani janë të hapura për Fondin e Mbështetjes së Medias Komunitare, me synim forcimin e mediave që shërbejnë komunitetet pakicë në Kosovë.</p><p>Aplikuesit e pranueshëm përfshijnë radio stacionet komunitare, portalet e lajmeve online dhe mediat e shtypura në gjuhët e pakicave. Grante deri në 15,000 EUR janë në dispozicion.</p>'],
                'type' => 'funding',
                'deadline' => now()->addDays(30),
            ],
            [
                'title' => ['en' => 'Commission Membership: Advisory Board for Community Languages', 'sq' => 'Anëtarësia në Komision: Bordi Këshillimor për Gjuhët e Komuniteteve'],
                'body' => ['en' => '<p>The Office for Community Issues invites applications for membership in the Advisory Board for Community Languages. The board provides recommendations on language policy implementation across Kosovo.</p><p>We seek representatives from each official community language group. Members serve a two-year term.</p>', 'sq' => '<p>Zyra për Çështje të Komuniteteve fton aplikime për anëtarësi në Bordin Këshillimor për Gjuhët e Komuniteteve. Bordi ofron rekomandime mbi zbatimin e politikave gjuhësore në Kosovë.</p><p>Kërkojmë përfaqësues nga çdo grup gjuhësor zyrtar i komuniteteve. Anëtarët shërbejnë me mandat dyvjeçar.</p>'],
                'type' => 'commission',
                'deadline' => now()->addDays(60),
            ],
        ];

        foreach ($calls as $data) {
            PublicCall::create(array_merge($data, [
                'slug' => Str::slug($data['title']['en']),
                'status' => 'published',
                'author_id' => 1,
            ]));
        }

        // ── Events ──
        $communityIds = Community::pluck('id', 'slug');

        $events = [
            ['title' => ['en' => 'Intercultural Festival Prishtina 2026', 'sq' => 'Festivali Ndërkulturor Prishtinë 2026'], 'description' => ['en' => 'A three-day festival celebrating the cultural diversity of Kosovo with music, dance, food, and art from all communities.', 'sq' => 'Një festival treditor që feston diversitetin kulturor të Kosovës me muzikë, vallëzim, ushqim dhe art nga të gjitha komunitetet.'], 'event_date' => now()->addDays(14), 'event_time' => '10:00', 'location' => 'Sheshi Skënderbeu, Prishtina', 'community_id' => null],
            ['title' => ['en' => 'Serbian Language and Literature Conference', 'sq' => 'Konferenca e Gjuhës dhe Letërsisë Serbe'], 'description' => ['en' => 'Annual conference on the preservation and promotion of Serbian language education in Kosovo schools.', 'sq' => 'Konferenca vjetore mbi ruajtjen dhe promovimin e arsimit në gjuhën serbe në shkollat e Kosovës.'], 'event_date' => now()->addDays(21), 'event_time' => '09:00', 'location' => 'Hotel Grand, Prishtina', 'community_id' => $communityIds['serbian'] ?? null],
            ['title' => ['en' => 'Roma Rights Awareness Workshop', 'sq' => 'Punëtoria e Ndërgjegjësimit për të Drejtat e Romëve'], 'description' => ['en' => 'A workshop focused on educating Roma community members about their legal rights, anti-discrimination protections, and available support services.', 'sq' => 'Një punëtori e fokusuar në edukimin e anëtarëve të komunitetit rom mbi të drejtat e tyre ligjore, mbrojtjet kundër diskriminimit dhe shërbimet mbështetëse në dispozicion.'], 'event_date' => now()->addDays(7), 'event_time' => '11:00', 'location' => 'Community Center, Fushë Kosovë', 'community_id' => $communityIds['roma'] ?? null],
            ['title' => ['en' => 'Turkish Cultural Heritage Exhibition', 'sq' => 'Ekspozita e Trashëgimisë Kulturore Turke'], 'description' => ['en' => 'Exhibition showcasing Ottoman-era architecture, calligraphy, and traditional Turkish crafts from the Prizren region.', 'sq' => 'Ekspozitë që paraqet arkitekturën e epokës osmane, kaligrafinë dhe zanatet tradicionale turke nga rajoni i Prizrenit.'], 'event_date' => now()->addDays(28), 'event_time' => '10:00', 'location' => 'Albanian League of Prizren Museum', 'community_id' => $communityIds['turkish'] ?? null],
            ['title' => ['en' => 'Bosniak Youth Leadership Camp', 'sq' => 'Kampi i Lidershipit të Rinisë Boshnjake'], 'description' => ['en' => 'A five-day leadership camp for young Bosniaks aged 18-25, focusing on civic engagement, project management, and community development.', 'sq' => 'Një kamp lidershipi pesëditor për të rinjtë boshnjakë të moshës 18-25 vjeç, i fokusuar në angazhimin qytetar, menaxhimin e projekteve dhe zhvillimin komunitar.'], 'event_date' => now()->addDays(35), 'event_time' => '08:00', 'location' => 'Brezovica Resort', 'community_id' => $communityIds['bosniak'] ?? null],
            ['title' => ['en' => 'Memorial Day: Ashkali Community Heritage Celebration', 'sq' => 'Dita Përkujtimore: Festimi i Trashëgimisë së Komunitetit Ashkali'], 'description' => ['en' => 'Annual celebration honoring the cultural heritage and contributions of the Ashkali community in Kosovo.', 'sq' => 'Festim vjetor që nderon trashëgiminë kulturore dhe kontributet e komunitetit ashkali në Kosovë.'], 'event_date' => now()->subDays(10), 'event_time' => '10:00', 'location' => 'Obiliq Cultural Center', 'community_id' => $communityIds['ashkali'] ?? null],
            ['title' => ['en' => 'Gorani Traditional Music Festival', 'sq' => 'Festivali i Muzikës Tradicionale Gorane'], 'description' => ['en' => 'Annual festival featuring traditional Gorani folk music, dance, and cuisine in the beautiful Sharr Mountains setting.', 'sq' => 'Festival vjetor me muzikë tradicionale popullore gorane, vallëzim dhe kuzhinë në peizazhin e bukur të Maleve të Sharrit.'], 'event_date' => now()->subDays(20), 'event_time' => '12:00', 'location' => 'Dragash Town Square', 'community_id' => $communityIds['gorani'] ?? null],
        ];

        foreach ($events as $data) {
            Event::create(array_merge($data, [
                'slug' => Str::slug($data['title']['en']),
            ]));
        }
    }
}
