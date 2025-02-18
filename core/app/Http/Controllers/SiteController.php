<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Betgame;
use App\Models\Frontend;
use App\Models\Game;
use App\Models\Language;
use App\Models\League;
use App\Models\Option;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\GameLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $totalUsers = User::count();
        $totalWin = GameLog::win()->count();

        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;

        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'totalUsers', 'totalWin'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function games()
    {
        $pageTitle = "Games";
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'games')->first();
        $games = Game::active()->get();
        return view('Template::games', compact('pageTitle', 'sections', 'games'));
    }
    public function search()
    {
        $pageTitle = "Games";
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'games')->first();
        $games = Game::active()->get();
        return view('Template::games', compact('pageTitle', 'sections', 'games'));
    }
    public function searchSubmit(Request $request)
    {
        $pageTitle = "Games Search Result";
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'games')->first();
        if ($request->search) {
            $games = Game::active()->searchable(['name'])->get();
        } else {
            $games = Game::active()->get();
        }
        return view('Template::games', compact('pageTitle', 'sections', 'games'));
    }
    public function searchgame(Request $request)
    {
        if ($request->search) {
            $games = Game::active()->searchable(['name'])->get();
        } else {
            $games = [];
        }
        return response()->json(['games' => $games]);
    }
    public function upload(Request $request)
    {
        if (sizeof($request->files)) {
            foreach ($request->files as $file) {
                try {
                    $path = 'assets/upload';
                    $filename = fileUploader($file, $path, filename: $file->getClientOriginalName());
                    return response()->json(['data' => "/" . $path . "/" . $filename]);
                } catch (\Exception $exp) {
                    $notify[] = ['errors', 'Image could not be uploaded'];
                    return response()->json($notify);
                }
            }
        } else {
            $notify[] = ['errors', 'Image could not be uploaded'];
            return response()->json($notify);
        }
    }
    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = @$sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('tempname', activeTemplateName())->where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function blog()
    {
        $blogs = Frontend::where('tempname', activeTemplateName())->where('data_keys', 'blog.element')->latest()->paginate(getPaginate(15));
        $pageTitle = 'Blog';
        $page = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        $sections = @$page->secs;
        return view('Template::blog', compact('blogs', 'pageTitle', 'sections'));
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $blog->views += 1;
        $blog->save();

        $pageTitle = 'Blog Detail';
        $latestBlogs = Frontend::where('id', '!=', $blog->id)->where('data_keys', 'blog.element')->orderBy('id', 'desc')->limit(5)->get();
        $mostViews = Frontend::where('id', '!=', $blog->id)->where('data_keys', 'blog.element')->orderBy('views', 'desc')->limit(5)->get();
        $seoContents = $blog->seo_content;

        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'latestBlogs', 'mostViews'));
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX = ($imgWidth - $textWidth) / 2;
        $textY = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $emailExist = Subscriber::where('email', $request->email)->first();
        if (!$emailExist) {
            $subscribe = new Subscriber();
            $subscribe->email = $request->email;
            $subscribe->save();
            return response()->json(['success' => 'Subscribed successfully']);
        } else {
            return response()->json(['error' => 'Already subscribed']);
        }
    }
    private function filterByLeagues($activeCategory, $gameType)
    {
        $leagues = $activeCategory->leagues();
        $gameType .= 'Game';
        return $leagues->withCount("$gameType as game_count")->orderBy('game_count', 'desc')->active()->get();
    }

    public function bet($categorySlug = null, $leagueSlug = null)
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Bet';
        $gameType = session('game_type', 'running');


        $games = Betgame::active()->$gameType();
        $categories = Category::getGames($gameType);

        if ($categorySlug) {
            $activeCategory = $categories->where('slug', $categorySlug)->first();
        } else {
            $activeCategory = $categories->where('games_count', $categories->max('games_count'))->first();
        }

        $leagues = [];
        $activeLeague = null;

        if ($leagueSlug) {
            $activeLeague = League::where('slug', $leagueSlug)->active()->whereHas('category', function ($q) {
                $q->active();
            })->firstOrFail();

            $activeCategory = $activeLeague->category;
        }

        if ($activeCategory && $activeCategory->leagues->count()) {
            $leagues = $this->filterByLeagues($activeCategory, $gameType);
            if (!$leagueSlug) {
                $activeLeague = $leagues->first();
            }
        }

        $games = $games->where('league_id', @$activeLeague->id)->with(['teamOne', 'teamTwo'])->with([
            'questions' => function ($q) {
                $q->active()
                    ->resultUndeclared()->select('id', 'game_id', 'title', 'locked')
                    ->withCount('betDetails')
                    ->with('options', function ($option) {
                        $option->active();
                    });
            }
        ])->orderBy('id', 'desc')->get();

        return view('Template::bet', compact('pageTitle', 'categories', 'leagues', 'games', 'activeCategory', 'activeLeague'));
    }


    public function switchType($type)
    {
        $url = url()->previous() ?? '/';
        // echo $type;
        session()->put('game_type', $type == 'live' ? 'running' : 'upcoming');
        return to_route('bet');
    }
    public function oddsType($type)
    {
        session()->put('odds_type', $type);
        return to_route('bet');
    }


    public function markets($gameSlug)
    {
        $gameType = session()->get('game_type', 'running');
        $game = Betgame::active()->$gameType()->where('slug', $gameSlug)->hasActiveCategory()->hasActiveLeague()
            ->with([
                'league',
                'questions' => function ($question) {
                    $question->active();
                    if (request()->more) {
                        $question->limit(request()->more);
                    }
                    $question->orderBy('id', 'desc')->resultUndeclared();
                },
                'questions.options' => function ($option) {
                    $option->active();
                },
            ])->firstOrFail();

        $categories = Category::getGames($gameType);
        $activeCategory = $game->league->category;
        $activeLeague = $game->league;
        $leagues = $this->filterByLeagues($activeCategory, $gameType);
        $pageTitle = "$game->slug - odds";
        return view('Template::markets', compact('pageTitle', 'categories', 'leagues', 'game', 'activeCategory', 'activeLeague'));
    }

    public function getOdds($id)
    {
        $options = Option::query();
        if (session('game_type') == 'running') {
            $options->availableForBet();
        }
        $options = $options->where('question_id', $id)->with('question')->get();
        return view('Template::partials.odds_by_question', compact('options'));
    }
    public function gamesByLeague($slug)
    {
        return $this->bet(leagueSlug: $slug);
    }
    public function gamesByCategory($slug)
    {
        return $this->bet(categorySlug: $slug);
    }
}
