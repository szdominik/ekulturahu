<?php if($this->session->userdata('level') >= 5): ?>
    <div class="alert alert-danger" role="alert">
        Figyelem! Az alábbi feladatok végrehajtása visszavonhatatlan következménnyel jár az ekultura.hu adatbázisára nézve.
        <br />
        Ha nem tudod, hogy mivel jár a végrehajtásuk, inkább ne tedd. Kérdezd a rendszer adminisztrátorát.
    </div>

    <div class="panel panel-default">
		<div class="panel-heading">Feladatok</div>
		<div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>
                            Sitemap generálás
                        </td>
                        <td>
                            <a href="/tasks/generate_sitemap_xml" target="_blank" type="button" class="btn btn-primary">
                                Elindít
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Cikkekben lévő felesleges (pl. western class, sorkizártság) formázás törlése
                        </td>
                        <td>
                            <a href="/tasks/refactor_article_body_formatting" target="_blank" type="button" class="btn btn-primary">
                                Elindít
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Régi, /cikkek_kepei képhivatkozások lecserélése újfajta (4-es verziójú) útvonalra
                        </td>
                        <td>
                            <a href="/tasks/cikkek_kepei_replace" target="_blank" type="button" class="btn btn-primary">
                                Elindít
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Régi, mutat.php-s linkek lecserélése az adatbázisból újfajta (4-es verziójú) linkekre
                        </td>
                        <td>
                            <a href="/tasks/remove_mutat_php_links" target="_blank" type="button" class="btn btn-primary">
                                Elindít
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
		</div>
	</div>

<?php endif; ?>
