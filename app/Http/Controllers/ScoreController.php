<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ScoreController extends Controller
{
    public function score(Request $request){
        $response = Http::get('https://api.github.com/users/'.$request->name.'/events/public');
        $results = $response->getBody()->getContents();
        $results = json_decode($results);

        if($response->successful()){
            $data = collect([]);
            foreach($results as $result){
                $element = [];
                //array_push($element, $result->type);
                $element['type'] = $result->type;

                $split = explode('/', $result->repo->name);
                //array_push($element, end($split));
                $element['repo'] = end($split);

                $created_at = substr($result->created_at, 0, 10);
                $created_at = Carbon::parse($created_at);
                $date = $created_at->format('F j, Y');
                //array_push($element, $date);
                $element['date'] = $date;

                if($result->type === "PushEvent"){
                    $points = 10;
                } elseif($result->type === "PullRequestEvent"){
                    $points = 5;
                } elseif($result->type === "IssueCommentEvent"){
                    $points = 4;
                } else {
                    $points = 1;
                }
                //array_push($element, $points);
                $element['points'] = $points;

                $data->push($element);
            }
        } else {
            return 'Not Found';
        }
        return response()->json([
            'elements' => $data,
        ]);
    }
}
