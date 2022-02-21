<?php
namespace app\Components;
use app\models\AuctionItem;
use app\models\ItemTemplate;
use yii\base\Component;

class WoWUtil extends Component {
    private function pluralize( $count, $text )
    {
        return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
    }

    public function ago($dt){
        $datetime = new \DateTime($dt);
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? ' ago' : '' );
        if ( $v = $interval->y >= 1 ) return $this->pluralize( $interval->y, 'year' ) . $suffix;
        if ( $v = $interval->m >= 1 ) return $this->pluralize( $interval->m, 'month' ) . $suffix;
        if ( $v = $interval->d >= 1 ) return $this->pluralize( $interval->d, 'day' ) . $suffix;
        if ( $v = $interval->h >= 1 ) return $this->pluralize( $interval->h, 'hour' ) . $suffix;
        if ( $v = $interval->i >= 1 ) return $this->pluralize( $interval->i, 'minute' ) . $suffix;
        return $this->pluralize( $interval->s, 'second' ) . $suffix;
    }
    public function printItemName($itemid,$faction="",$realm =""){
        if($faction == "")
            $faction = \Yii::$app->session->get("faction");
        if($realm == "")
            $realm = \Yii::$app->session->get("realm");
        $item = ItemTemplate::findOne(['entry'=>$itemid]);
        $str = "<a style='color:".$item->color."' rel='item=".$item->entry."' href='".\Yii::$app->urlManager->createUrl(["item/index",'id'=>$item->entry,'faction'=>$faction,'realm'=>$realm])."'>[".$item->name."]</a>";
        return $str;
    }
    public function printItem($itemid,$faction="",$realm=""){
        $colors = ['grey','black','green','blue','purple','orange','red','gold'];
        $names = ['Poor','Common','Uncommon','Rare','Epic','Legendary','Artifact','Bind to Account'];
        if($faction == "")
            $faction = \Yii::$app->session->get("faction");
        if($realm == "")
            $realm = \Yii::$app->session->get("realm");

        $item = ItemTemplate::findOne(['entry'=>$itemid]);
        if($item == NULL){
            return "Not Found";
        }
        else {
            return "
            <table class='table table-borderless'>
                <tr>
                    <td style='padding:0px'>
                        <div class='mediumicon'>
                            <div style='width:36px; height:36px; left:4px; top:4px; background-image:url(/images/ICONS/".$item->icon->icon_name.".PNG)'></div>
                        </div>
                    </td>
                    <td style='padding:0px'>
                        <a style='color:".$item->color."' rel='item=".$item->entry."' href='".\Yii::$app->urlManager->createUrl(["item/index",'id'=>$item->entry,'faction'=>$faction,'realm'=>$realm])."'>[".$item->name."]</a>
                    </td>
                </tr>
            </table>           
            ";
        }
    }

    public function printTrend($val){
        $val2 = round($val * 100,0);
        if($val2 > 0){
            return "<div class='text-success'><div class='glyphicon glyphicon-arrow-up'></div> $val2 %</div>";
        }
        else if($val2 < 0){
            return "<div class='text-danger'><div class='glyphicon glyphicon-arrow-down'></div> $val2 %</div>";
        }
        else
            return "";
    }

    public function printCurrency($amt) {
        $oamt = $amt;
        $c = $amt % 100;
        $amt = floor($amt/100);
        $s = $amt % 100;
        $amt = floor($amt/100);
        $g = $amt;
        return (($g > 0)?"<span class='currencygold'>$g</span>":"").(($s > 0)?"<span class='currencysilver'>$s</span>":"").(($c > 0)?"<span class='currencycopper'>$c</span>":"");
    }
}
?>