class AppWebService {
	
	endpoint = '/api';
	
	static METHOD = {
		'GET': 'GET',
		'POST': 'POST',
		'PUT': 'PUT',
		'DELETE': 'DELETE',
	};
	
	async fetch(path, method, body, headers) {
		const parameters = {headers: {}};
		if( method ) {
			parameters.method = method;
		}
		if( headers ) {
			parameters.headers = headers;
		}
		if( body ) {
			parameters.headers["Content-Type"] = "application/json";
			parameters.body = JSON.stringify(body);
		}
		const response = await fetch(this.endpoint + path, parameters);
		if( !response.ok ) {
			let body = await response.json().catch(() => response.text());
			if( isObject(body) ) {
				body = body.description;
			}
			throw new Error(`Invalid server response : [${response.status} ${response.statusText}] ${body}`);
		}
		return await response.json();
	}
	
	
	listDemoEntities() {
		return this.fetch('/demo-entity');
	}
	
	createDemoEntity(input) {
		return this.fetch('/demo-entity', AppWebService.METHOD.POST, input);
	}
	
	updateDemoEntity(id, input) {
		return this.fetch('/demo-entity/' + id, AppWebService.METHOD.PUT, input);
	}
	
	removeDemoEntity(id) {
		return this.fetch('/demo-entity/' + id, AppWebService.METHOD.DELETE);
	}
	
	getAuthenticatedUser(token) {
		const headers = {};
		if( token ) {
			headers['Authorization'] = 'Basic ' + token;
		}
		return this.fetch('/me', null, null, headers);
	}
	
	parseDate(date) {
		return new Date(date)
	}
}

Object.freeze(AppWebService.METHOD);

class TestApiController {
	constructor() {
		this.items = {};// Dictionary
		this.webService = new AppWebService();
		this.$userInformationForm = document.querySelector('#FormUserInformation');
		this.$userInformationFieldset = document.querySelector('#FieldsetUser');
		this.$userInformationAlertError = document.querySelector('#AlertUserInformationError');
		this.$userInformationAlertLoading = document.querySelector('#AlertUserInformationLoading');
		this.$entityList = document.querySelector('#ListDemoEntity');
		this.$updateForm = document.querySelector('#FormDemoEntityUpdate');
		const $entityItemTemplate = document.querySelector('#TemplateDemoEntityItem');
		const $listPlaceholderTemplate = document.querySelector('#TemplateDemoEntityPlaceholder');
		this.list = new ModelList(false);
		this.list.assignList(this.$entityList, $entityItemTemplate, $listPlaceholderTemplate);
	}
	
	start() {
		this.bindEvents();
		this.refreshList();
		this.$updateForm.hidden = true;
	}
	
	get(id) {
		return this.items[id];
	}
	
	openItem(id) {
		// Fill
		domService.fillForm(this.$updateForm, this.get(id));
		this.$updateForm.dataset.itemId = id;
		// Show
		this.$updateForm.hidden = false;
	}
	
	closeItem() {
		// Hide
		this.$updateForm.hidden = true;
		// Reset
		this.$updateForm.reset();
		delete this.$updateForm.dataset.itemId;
	}
	
	async createItem(input) {
		await this.webService.createDemoEntity(input);
		this.refreshList();
	}
	
	async updateItem(id, input) {
		await this.webService.updateDemoEntity(id, input);
		this.refreshList();
	}
	
	async removeItem(id) {
		const removed = await this.webService.removeDemoEntity(id);
		if( removed ) {
			this.refreshList();
		}
	}
	
	async getUserInformation(token) {
		return await this.webService.getAuthenticatedUser(token);
	}
	
	async refreshList() {
		this.$entityList.classList.add('loading');
		try {
			const entities = await this.webService.listDemoEntities();
			for (const entity of Object.values(entities)) {
				entity.createDate = this.webService.parseDate(entity.create_date);
				entity.createDateText = entity.createDate.toLocaleDateString(undefined, {year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric'});
			}
			this.items = entities;
			this.list.empty();
			this.list.loadList(Object.values(entities));
		} finally {
			this.$entityList.classList.remove('loading');
		}
	}
	
	bindEvents() {
		document.querySelectorAll('.form-demo-entity')
			.forEach($form => {
				
				$form.addEventListener('submit', async event => {
					event.preventDefault();
					$form.classList.add('was-validated');
					if( $form.checkValidity() ) {
						const input = domService.getFormObject($form);
						const action = $form.dataset.action;
						if( action === 'update' ) {
							await this.updateItem(this.$updateForm.dataset.itemId, input);
						} else {
							await this.createItem(input);
						}
						domService.resetForm($form);
					}
				});
				
			});
		
		this.$userInformationForm.addEventListener('submit', async event => {
			event.preventDefault();
			const input = domService.getFormObject(this.$userInformationForm);
			try {
				this.$userInformationFieldset.hidden = true;
				this.$userInformationAlertError.hidden = true;
				this.$userInformationAlertLoading.hidden = false;
				const user = await this.getUserInformation(input.token.trim());
				console.log("user", user);
				domService.fillForm(this.$userInformationFieldset, user);
				this.$userInformationFieldset.hidden = false;
			} catch (error) {
				this.$userInformationAlertError.innerText = error.message;
				this.$userInformationAlertError.hidden = false;
			} finally {
				this.$userInformationAlertLoading.hidden = true;
			}
		});
		
		domService.on(this.$entityList, "click", '[data-action="open-entity"]', $element => this.openItem($element.dataset.itemId));
		domService.on(this.$entityList, "confirmed", '[data-action="remove-entity"]', $element => this.removeItem($element.dataset.itemId));
		domService.on('[data-action="create"]', "click", '[data-action="cancel"]', ($element, $form) => domService.resetForm($form));
		domService.on('[data-action="update"]', "click", '[data-action="cancel"]', () => this.closeItem());
		domService.on(this.$userInformationForm, "click", '[data-action="cancel"]', () => {
			this.$userInformationFieldset.hidden = true;
			this.$userInformationAlertError.hidden = true;
			domService.resetForm(this.$userInformationForm);
		});
	}
}

ready(() => {
	const testApiController = new TestApiController();
	window.testApiController = testApiController;
	testApiController.start();
})
