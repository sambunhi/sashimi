<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use Carbon\Carbon;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('articles')->insert([
            ['id'=>1, 'nltk_at'=>Carbon::now() ,'title'=>'全球晶片產業都仰賴它——半導體設備大廠ASML虎年禮盒，訴說一個晶片的誕生', 'url'=>'https://www.thenewslens.com/article/161983', 'source_id'=>4,'published_at'=>Carbon::now()->addDays(1)->format('Y-m-d')],
            ['id'=>2, 'nltk_at'=>Carbon::now() ,'title'=>'為突破美國禁令，傳中國將斥資百億美元替華為、中芯牽線，尋求台積電供應鏈協助蓋晶圓廠', 'url'=>'https://www.thenewslens.com/article/160937', 'source_id'=>4,'published_at'=>Carbon::now()->addDays(2)->format('Y-m-d')],
            ['id'=>3, 'nltk_at'=>Carbon::now() ,'title'=>'美國擬擴大對中芯國際的出口禁令，業內人士：市占率恐進一步喪失，轉單效應有利台廠', 'url'=>'https://www.thenewslens.com/article/160355', 'source_id'=>4,'published_at'=>Carbon::now()->addDays(3)->format('Y-m-d')],
            ['id'=>4, 'nltk_at'=>Carbon::now() ,'title'=>'俄羅斯入侵烏克蘭13天、200萬難民逃離，美英宣布禁運俄國石油', 'url'=>'https://www.twreporter.org/a/russia-ukraine-war-2022-03-09', 'source_id'=>3,'published_at'=>Carbon::now()->addDays(1)->format('Y-m-d')],
            ['id'=>5, 'nltk_at'=>Carbon::now() ,'title'=>'為何掉入「為普丁買單」的兩難？專訪三黨國會議員，俄烏戰爭給德國的慘痛一課', 'url'=>'https://www.twreporter.org/a/russian-invasion-of-ukraine-2022-germany-a-brutal-lesson', 'source_id'=>3,'published_at'=>Carbon::now()->addDays(2)->format('Y-m-d')],
            ['id'=>6, 'nltk_at'=>Carbon::now() ,'title'=>'越洋專訪烏克蘭「平民戰士」：開戰後關鍵5天，他們如何互助、轉身抗戰', 'url'=>'https://www.twreporter.org/a/russia-ukraine-war-2022-civilian-fight', 'source_id'=>3,'published_at'=>Carbon::now()->addDays(3)->format('Y-m-d')],
            ['id'=>7, 'nltk_at'=>Carbon::now() ,'title'=>'矽晶圓價值已被低估', 'url'=>'https://udn.com/news/story/6851/6356339', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(1)->format('Y-m-d')],
            ['id'=>8, 'nltk_at'=>null, 'title'=>'三星傳擬漲價最高20％ 業者：晶圓代工仍是賣方市場', 'url'=>'https://udn.com/news/story/7240/6311878', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(2)->format('Y-m-d')],
            ['id'=>9, 'nltk_at'=>null, 'title'=>'TECHCET：全球矽晶圓緊缺2024年前難有緩解', 'url'=>'https://udn.com/news/story/7240/6231227', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(3)->format('Y-m-d')],
            ['id'=>10, 'nltk_at'=>null, 'title'=>'矽晶圓擴產不停歇 提防兩變數', 'url'=>'https://udn.com/news/story/7240/6211873', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(1)->format('Y-m-d')],
            ['id'=>11, 'nltk_at'=>null, 'title'=>'元大全球5G ETF 績效亮眼', 'url'=>'https://fund.udn.com/fund/story/5860/6337888', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(2)->format('Y-m-d')],
            ['id'=>12, 'nltk_at'=>null, 'title'=>'從思科到應材 大陸清零封控對科技業衝擊才正開始浮現', 'url'=>'https://money.udn.com/money/story/122381/6327319', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(3)->format('Y-m-d')],
            ['id'=>13, 'nltk_at'=>null, 'title'=>'像荷葉一樣的包裝－以荷葉為靈感，做出可分解塑料', 'url'=>'https://ubrand.udn.com/ubrand/story/12116/6273371', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(1)->format('Y-m-d')],
            ['id'=>14, 'nltk_at'=>null, 'title'=>'應用材料財測不如預期 大陸防疫封控導致零件短缺', 'url'=>'https://udn.com/news/story/7333/6326888', 'source_id'=>2,'published_at'=>Carbon::now()->addDays(2)->format('Y-m-d')]
        ]);

        DB::table('trends')->insert([
            ['article_id'=>1, 'keyword'=>'ASML', 'cnt'=>5],
            ['article_id'=>2, 'keyword'=>'ASML', 'cnt'=>2],
            ['article_id'=>3, 'keyword'=>'ASML', 'cnt'=>7],
            ['article_id'=>4, 'keyword'=>'烏克蘭', 'cnt'=>2],
            ['article_id'=>5, 'keyword'=>'烏克蘭', 'cnt'=>5]
        ]);
    }
}
