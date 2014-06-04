<?php namespace LemonTree;

$site = \App::make('site');

$site->initMicroTime();

$site->

	/*
	 * Категория товаров
	 */

	addItem(
		Item::create('Category')->
		setTitle('Категория товаров')->
		setMainProperty('name')->
		setRoot(true)->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			TextareaProperty::create('shortcontent')->
			setTitle('Краткое описание')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Полное описание')
		)->
		addProperty(
			CheckboxProperty::create('hide')->
			setTitle('Скрыть')->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Подкатегория товаров
	 */

	addItem(
		Item::create('Subcategory')->
		setTitle('Подкатегория товаров')->
		setMainProperty('name')->
		setRoot(true)->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Полное описание')
		)->
		addProperty(
			CheckboxProperty::create('hide')->
			setTitle('Скрыть')->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('category_id')->
			setTitle('Категория товаров')->
			setRelatedClass('Category')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Раздел сайта
	 */

	addItem(
		Item::create('Section')->
		setTitle('Раздел сайта')->
		setMainProperty('name')->
		setRoot(true)->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('url')->
			setTitle('Адрес страницы')
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			TextfieldProperty::create('h1')->
			setTitle('H1')
		)->
		addProperty(
			TextfieldProperty::create('meta_keywords')->
			setTitle('META Keywords')
		)->
		addProperty(
			TextareaProperty::create('meta_description')->
			setTitle('META Description')
		)->
		addProperty(
			TextareaProperty::create('shortcontent')->
			setTitle('Краткий текст')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Текст раздела')
		)->
		addProperty(
			OneToOneProperty::create('section_id')->
			setTitle('Раздел сайта')->
			setRelatedClass('Section')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Служебный раздел
	 */

	addItem(
		Item::create('ServiceSection')->
		setTitle('Служебный раздел')->
		setMainProperty('name')->
		setRoot(true)->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('ServiceSection')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Настройки сайта
	 */

	addItem(
		Item::create('SiteSettings')->
		setTitle('Настройки сайта')->
		setMainProperty('name')->
		setRoot(true)->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')->
			setRequired(true)
		)->
		addProperty(
			TextfieldProperty::create('h1')->
			setTitle('H1')
		)->
		addProperty(
			TextareaProperty::create('description')->
			setTitle('META Description')
		)->
		addProperty(
			TextfieldProperty::create('keywords')->
			setTitle('META Keywords')
		)->
		addProperty(
			RichtextProperty::create('text')->
			setTitle('Текст')
		)->
		addProperty(
			TextfieldProperty::create('copyright')->
			setTitle('Copyright')
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setShow(true)->
			setReadonly(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setShow(true)->
			setReadonly(true)
		)
	)->

	/*
	 * Счетчик
	 */

	addItem(
		Item::create('Counter')->
		setTitle('Счетчик')->
		setMainProperty('name')->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextareaProperty::create('code')->
			setTitle('Код счетчика')
		)->
		addProperty(
			TextareaProperty::create('logo')->
			setTitle('Логотип счетчика')
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('ServiceSection')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setReadonly(true)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Категория расходов
	 */

	addItem(
		Item::create('ExpenseCategory')->
		setTitle('Категория расходов')->
		setMainProperty('name')->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('ServiceSection')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setReadonly(true)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Источник расходов
	 */

	addItem(
		Item::create('ExpenseSource')->
		setTitle('Источник расходов')->
		setMainProperty('name')->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('ServiceSection')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setReadonly(true)->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	/*
	 * Расход
	 */

	addItem(
		Item::create('Expense')->
		setTitle('Расход')->
		setMainProperty('name')->
		addOrderBy('order', 'asc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextareaProperty::create('comment')->
			setTitle('Примечание')
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('ServiceSection')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setReadonly(true)->
			setParent(true)
		)->
		addProperty(
			OneToOneProperty::create('expense_category_id')->
			setTitle('Категория расходов')->
			setRelatedClass('ExpenseCategory')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setRequired(true)
		)->
		addProperty(
			OneToOneProperty::create('expense_source_id')->
			setTitle('Источник расходов')->
			setRelatedClass('ExpenseSource')->
			setDeleting(OneToOneProperty::RESTRICT)->
			setRequired(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
			setTitle('Дата создания')->
			setReadonly(true)->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Последнее изменение')->
			setReadonly(true)->
			setShow(true)
		)
	)->

	bind(Site::ROOT, 'Category')->
	bind('Category', 'Subcategory')->
	bind(Site::ROOT, 'Section')->
	bind(Site::ROOT, 'ServiceSection')->
	bind('ServiceSection.1', 'ServiceSection')->
	bind('ServiceSection.4', 'Counter')->
	bind('ServiceSection.7', 'ServiceSection')->
	bind('ServiceSection.12', 'ExpenseCategory')->
	bind('ServiceSection.13', 'ExpenseSource')->

	bindTree(Site::ROOT, 'Category')->
	bindTree('Category', 'Subcategory')->
	bindTree(Site::ROOT, 'Section')->
	bindTree(Site::ROOT, 'ServiceSection')->
	bindTree(Site::ROOT, 'SiteSettings')->
	bindTree('Section', 'Section')->
	bindTree('ServiceSection.1', 'ServiceSection')->
	bindTree('ServiceSection.4', 'Counter')->
	bindTree('ServiceSection.7', 'ServiceSection')->
	bindTree('ServiceSection.12', 'ExpenseCategory')->
	bindTree('ServiceSection.13', 'ExpenseSource')->

	end();
