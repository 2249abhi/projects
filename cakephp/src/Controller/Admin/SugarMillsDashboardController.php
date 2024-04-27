<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SugarMillsDashboardController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $is_first_login = $this->Auth->user('is_first_login');
        if($is_first_login=='1')
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'changePassword']);
        }
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {        
        $this->loadMOdel('FederationSugarMills');  
        $role_id =  $this->Auth->user('role_id');        
   
        $query = $this->FederationSugarMills->find();

        $total_closed = $query->newExpr()->addCase($query->newExpr()->add(['operational_status' => 'Closed']), 1, 'integer');        
        $total_running = $query->newExpr()->addCase($query->newExpr()->add(['operational_status' => 'Running']), 1,'integer' );       
        $total_windup = $query->newExpr()->addCase($query->newExpr()->add(['operational_status' => 'Windup']), 1,'integer' );
        $total_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['operational_status' => 'Liquidation']), 1,'integer' );
        //
        $private_ownership = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Private']), 1,'integer' );
        $cooperative_ownership = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Cooperative']), 1,'integer' );
        $windup_ownership = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Windup']), 1,'integer' );
        $leased_ownership = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Leased']), 1,'integer' );
        $liquidation_ownership = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Liquidation']), 1,'integer' );
        //
        $private_ownership_closed = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Private', 'operational_status' => 'Closed']), 1,'integer' );
        $private_ownership_running = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Private', 'operational_status' => 'Running']), 1,'integer' );
        $private_ownership_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Private', 'operational_status' => 'Liquidation']), 1,'integer' );
        $private_ownership_windup = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Private', 'operational_status' => 'Windup']), 1,'integer' );
        //
        $cooperative_ownership_closed = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Cooperative', 'operational_status' => 'Closed']), 1,'integer' );
        $cooperative_ownership_running = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Cooperative', 'operational_status' => 'Running']), 1,'integer' );
        $cooperative_ownership_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Cooperative', 'operational_status' => 'Liquidation']), 1,'integer' );
        $cooperative_ownership_windup = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Cooperative', 'operational_status' => 'Windup']), 1,'integer' );
        //
        $windup_ownership_closed = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Windup', 'operational_status' => 'Closed']), 1,'integer' );
        $windup_ownership_running = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Windup', 'operational_status' => 'Running']), 1,'integer' );
        $windup_ownership_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Windup', 'operational_status' => 'Liquidation']), 1,'integer' );
        $windup_ownership_windup = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Windup', 'operational_status' => 'Windup']), 1,'integer' );
        //        
        $leased_ownership_closed = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Leased', 'operational_status' => 'Closed']), 1,'integer' );
        $leased_ownership_running = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Leased', 'operational_status' => 'Running']), 1,'integer' );
        $leased_ownership_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Leased', 'operational_status' => 'Liquidation']), 1,'integer' );
        $leased_ownership_windup = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Leased', 'operational_status' => 'Windup']), 1,'integer' );
        //
        $liquidation_ownership_closed = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Liquidation', 'operational_status' => 'Closed']), 1,'integer' );
        $liquidation_ownership_running = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Liquidation', 'operational_status' => 'Running']), 1,'integer' );
        $liquidation_ownership_liquidation = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Liquidation', 'operational_status' => 'Liquidation']), 1,'integer' );
        $liquidation_ownership_windup = $query->newExpr()->addCase($query->newExpr()->add(['ownership_status' => 'Liquidation', 'operational_status' => 'Windup']), 1,'integer' );        
        //
        $mills_profit = $query->newExpr()->addCase($query->newExpr()->add(['profit_loss >' => '0']), 1,'integer' );
        $mills_loss = $query->newExpr()->addCase($query->newExpr()->add(['profit_loss <' => '0']), 1,'integer' );
        $mills_neutral = $query->newExpr()->addCase($query->newExpr()->add(['profit_loss' => '0']), 1,'integer' );
        //

        $result= $query->select([
        'Closed' => $query->func()->count($total_closed),
        'Running' => $query->func()->count($total_running),
        'Liquidation' => $query->func()->count($total_liquidation),
        'Windup' => $query->func()->count($total_windup),

        'private_ownership' => $query->func()->count($private_ownership),        
        'cooperative_ownership' => $query->func()->count($cooperative_ownership),
        'windup_ownership' => $query->func()->count($windup_ownership),
        'leased_ownership' => $query->func()->count($leased_ownership),
        'liquidation_ownership' => $query->func()->count($liquidation_ownership),

        'private_ownership_closed' => $query->func()->count($private_ownership_closed),
        'private_ownership_running' => $query->func()->count($private_ownership_running),
        'private_ownership_liquidation' => $query->func()->count($private_ownership_liquidation),
        'private_ownership_windup' => $query->func()->count($private_ownership_windup),

        'cooperative_ownership_closed' => $query->func()->count($cooperative_ownership_closed),
        'cooperative_ownership_running' => $query->func()->count($cooperative_ownership_running),
        'cooperative_ownership_liquidation' => $query->func()->count($cooperative_ownership_liquidation),
        'cooperative_ownership_windup' => $query->func()->count($cooperative_ownership_windup),

        'windup_ownership_closed' => $query->func()->count($windup_ownership_closed),
        'windup_ownership_running' => $query->func()->count($windup_ownership_running),
        'windup_ownership_liquidation' => $query->func()->count($windup_ownership_liquidation),
        'windup_ownership_windup' => $query->func()->count($windup_ownership_windup),

        'leased_ownership_closed' => $query->func()->count($leased_ownership_closed),
        'leased_ownership_running' => $query->func()->count($leased_ownership_running),
        'leased_ownership_liquidation' => $query->func()->count($leased_ownership_liquidation),
        'leased_ownership_windup' => $query->func()->count($leased_ownership_windup),

        'liquidation_ownership_closed' => $query->func()->count($liquidation_ownership_closed),
        'liquidation_ownership_running' => $query->func()->count($liquidation_ownership_running),
        'lliquidation_ownership_liquidation' => $query->func()->count($liquidation_ownership_liquidation),
        'liquidation_ownership_windup' => $query->func()->count($liquidation_ownership_windup),

        'mills_neutral' => $query->func()->count($mills_neutral),
        'mills_profit' => $query->func()->count($mills_profit),
        'mills_loss' => $query->func()->count($mills_loss),

        ])->toArray();

        $mill_status=$result[0];

$actual_capacity=$this->FederationSugarMills->find('all')->select(['actual_capacity'=>'sum(FederationSugarMills.actual_capacity)', 'States.name'])->contain(['States'])->group('FederationSugarMills.state_code')->order(['States.name'=>'ASC'])->toArray();


$ethanol_cogen_capacity=$this->FederationSugarMills->find('all')->select(['ethanol_capacity'=>'sum(FederationSugarMills.ethanol_capacity)','cogen_capacity'=>'sum(FederationSugarMills.cogen_capacity)', 'States.name'])->where(["FederationSugarMills.ownership_status"=>'Cooperative'])->contain(['States'])->group('FederationSugarMills.state_code')->order(['States.name'=>'ASC'])->toArray();

$this->set(compact('mill_status','role_id', 'actual_capacity', 'ethanol_cogen_capacity'));

/*
//pp(TODAY_NOW); // "Asia/Calcutta"  , America/Los_Angeles, Asia/Tokyo,  America/New_York, 
echo "Del ".TODAY_NOW;
echo '<br>';
echo "Ny ".changeTimeZone(TODAY_NOW, "", "America/New_York");
echo '<br>';
echo "Tk ".changeTimeZone(TODAY_NOW, "", "Asia/Tokyo");
echo '<br>';
echo "Sea ".changeTimeZone(TODAY_NOW, "", "America/Los_Angeles");
echo '<br>';
//echo "Del ".TODAY_NOW;
echo $tt3=changeTimeZone(TODAY_NOW, "Asia/Tokyo", "America/New_York" );


$tt1=changeTimeZone(TODAY_NOW, "Asia/Tokyo"); // So, to convert to the server default, you would just pass one timezone:
pp($tt1); echo 'curr tokyo';

$tt2=changeTimeZone(TODAY_NOW, "", "Asia/Tokyo"); // To convert from the server default to the user, you would leave the 2nd parameter null or blank:
pp($tt2); pp($tt1); echo 'chg to tokyo';

$tt3=changeTimeZone(TODAY_NOW, "America/New_York", "Asia/Tokyo"); // And to switch between 2 timezones unrelated to the default, you would provide 2 timezones:
    pp($tt3); echo 'ny to tokyo';

    pp(TODAY_NOW); */



    /*  
    SELECT COUNT(CASE WHEN published = 'Y' THEN 1 END) AS number_published, COUNT(CASE WHEN published = 'N' THEN 1 END) AS number_unpublished FROM articles;
    $count_text="  SELECT 
    sum(CASE WHEN FSM.operational_status='Closed' THEN 1 ELSE 0 END) AS Closed,
    sum(CASE WHEN FSM.operational_status='Running' THEN 1 ELSE 0 END) AS Running,
    sum(CASE WHEN FSM.operational_status='Liquidation' THEN 1 ELSE 0 END) AS Liquidation,
    sum(CASE WHEN FSM.operational_status='Windup' THEN 1 ELSE 0 END) AS Windup
    FROM federation_sugar_mills FSM ";
    $result=$this->query($count_text);
    */       
    }

//-------------------------------------------

    function query($text) 
    {
            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute($text);
            $results = $stmt ->fetchAll('assoc');
            return $results;
    }
   
    /*
      $Gis_Controller = new GisController();
      $Gis_Controller->current_scheme='pacs';
      $pacs_count=$Gis_Controller->getSchemeCount(1); 
    */

    
}