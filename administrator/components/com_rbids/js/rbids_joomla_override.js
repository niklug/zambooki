function rbids_listItemTask(id, task) {
	var f = document.adminForm;
	var cb = f[id];
	if (cb) {
		for (var i = 0; true; i++) {
			var cbx = f['cb'+i];
			if (!cbx)
				break;
			cbx.checked = false;
		} // for
		cb.checked = true;
		f.boxchecked.value = 1;
		Joomla.submitbutton(task);
	}
	return false;
}
