<?php namespace LemonTree;

class ServiceController extends BaseController {

	public function getService()
	{
		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		$sequences = \DB::select("select relname from pg_class where relkind='S'");

		foreach ($sequences as $sequence) {
			$seqName = $sequence->relname;
			$tableName = str_replace('_id_seq', '', $seqName);
			\DB::statement("SELECT setval('$seqName', (SELECT MAX(id) FROM $tableName) + 1)");
			echo $sequence->relname.'<br />';
		}

		return 'OK. Complete.';
	}

}