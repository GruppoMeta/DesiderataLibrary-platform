<?php
class gruppometa_jobmanager_views_components_JobsReport extends org_glizy_components_ComponentContainer
{
    private $it;

    function process()
    {
        // aggiorna lo stato dei job eseguiti in background
        $it = org_glizy_objectFactory::createModelIterator('gruppometa.jobmanager.models.Job')
            ->load('runningBackgroundJobs');

        /*
        foreach ($it as $ar) {
            $jobService = org_glizy_objectFactory::createObject($ar->job_name, $ar->job_id);
            $jobService->update();
        }
        */

        $this->it = org_glizy_objectFactory::createModelIterator('gruppometa.jobmanager.models.Job')
                  ->load('report');

        $c = $this->getAttribute('paginate');
        if (is_object($c)) {
            $c->setRecordsCount();
            $pageLimits = $c->getLimits();

            $this->it->limit($pageLimits['start'], $pageLimits['pageLength']);
            $c->setRecordsCount($this->it->count());
        }
    }

    function render()
    {
        $canShowId = $this->_user->acl('jobmanager', 'showId');
        $canDelete = $this->_user->acl('jobmanager', 'delete');
        $canRefresh = $this->_user->acl('jobmanager', 'refresh');

        $output .= '<table id="'.$this->getAttribute('id').'" class="table table-bordered table-striped">';
        $output .= '<thead><tr>'.
                    ($canShowId ? '<th>id</th>' : '').
                    '<th>Descrizione</th><th>Stato</th><th style="text-align:center">Avanzamento</th><th style="text-align:center">Data di modifica</th><th style="width:500px;text-align:center">Messaggi</th><th></th></tr></thead>';
        $output .= '<tbody>';

        foreach ($this->it as $ar) {
            $status = gruppometa_jobmanager_JobStatus::getDescription($ar->job_status);
            $output .= '<tr>'.
                        ($canShowId ? '<td>'.$ar->job_id.'</td>' : '').
                        '<td>'.$ar->job_description.'</td>'.
                        '<td nowrap>'.$status.'</td>'.
                        '<td style="text-align:center">'.$ar->job_progress.'%</td>'.
                        '<td nowrap>'.$ar->job_modificationDate.'</td>'.
                        '<td>'.$ar->job_message.'</td>'.
                        '<td nowrap>'.
                        ($ar->job_status!='RUNNING' && $canDelete ? '<a href="#" class="js-delete" data-id="'.$ar->job_id.'"><i class="icon-trash btn-icon"></i> </a>' : '').
                        ($canRefresh ? '<a href="#" class="js-refresh" data-id="'.$ar->job_id.'"><i class="icon-refresh btn-icon"></i> </a>' : '').
                        '</td>'.
                        '</tr>';
        }

        $output .= '</tbody>';
        $output .= "</table>";
        $this->addOutputCode($output);


        if (!__Request::exists('ajax')) {
            $js = <<<EOD
$( document ).ready( function(){
    // aggiorna il report ogni 10 secondi
    var UPDATE_INTERVAL = 10;

    setInterval(updateReport, UPDATE_INTERVAL*1000);

    function updateReport() {
        $.ajax( {
            url: Glizy.ajaxUrl+"updateReport&ajax=true",
            success: function( data ) {
                $( "#report" ).html( data );
                $( "#report" ).show( );
            }
        });
    }

    $(document).on('click', 'a.js-delete', function(e) {
        e.preventDefault();
        if (confirm('Sei sicuro di cancellae il Job?')) {
            $.ajax( {
                url: Glizy.ajaxUrl+"deleteJob",
                data: {id: $(this).data('id')},
                success: function( data ) {
                    updateReport();
                }
            });
        }
    });

    $(document).on('click', 'a.js-refresh', function(e) {
        e.preventDefault();
        $.ajax( {
            url: Glizy.ajaxUrl+"refreshJob",
            data: {id: $(this).data('id')},
            success: function( data ) {
                updateReport();
            }
        });
    });


});
EOD;

            $this->addOutputCode(org_glizy_helpers_JS::JScode($js));
        }

    }
}
