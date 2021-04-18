
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script crossorigin src="https://unpkg.com/react@17/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.0.0-beta.3/babel.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<body>
	<Center>
		<div class="container"></div>
	</Center>
</body>





<script type="text/babel">
class TodoApp extends React.Component {
	constructor(props){
		super(props);
		this.state = {
			appName: 'To do List Sample',
			taskValue: '',
			todo: []
		};

		this.changeHandler = this.changeHandler.bind(this);
    	this.submitHandler = this.submitHandler.bind(this);

    	this.getTodoList();

	}

	getTodoList(){
		let todoIndexUrl = window.location.toString() + 'task';
		axios.get(todoIndexUrl)
		.then(response => {  
		console.log(response.data.response);
			this.setState({todo:response.data.response});

		})
	}


	changeHandler(event){
		this.setState({taskValue: event.target.value});
	}

	submitHandler(event){
		let addTaskUrl = window.location.toString() + 'task/addTask';
		let requestBody = {
			'task':this.state.taskValue,
			'code': 200
		}

		axios.post(addTaskUrl,requestBody)
		.then(response => {
			console.log({'task':this.state.taskValue,'id':response.data.response.latestId});
			this.state.todo.push({'task':this.state.taskValue,'id':response.data.response.latestId});
			this.setState({todo:this.state.todo});
		})
		
    	event.preventDefault();	
	}


	deleteTaskHandler(id){
		let deleteTaskUrl = window.location.toString() + 'task/delete';
		let requestBody = {
			'taskId': id,
			'code': 200
		};

		axios.post(deleteTaskUrl, requestBody)
		.then(response => {
			console.log(response);
			console.log(response.data.response.msg);
			let todoArr = this.state.todo;
			for (let i = 0; i < todoArr.length; i++){
				if(todoArr[i]['id'] == id){
					todoArr.splice(i, 1);
					this.setState({todo:todoArr});
				}
			}
		}).catch(err => console.log(err))
	}


	render(){
		return(
			<div className="row">
				<div className="col s4 offset-s4">
					<h1>{this.state.appName}</h1>
					<ul className="collection">
				      {this.state.todo.map((list) => <li className="collection-item" key={list.id}><div>{list.task}<a className="secondary-content" onClick={() => this.deleteTaskHandler(list.id)}><i className="material-icons">X</i></a></div></li>)}
				    </ul> 
				    <form onSubmit={this.submitHandler}>
						<label>
				          	Enter Task:
				         	<input type="text" value={this.state.taskValue} onChange={this.changeHandler} />        
				     	</label>
				     	<input className="waves-effect waves-light btn" type="submit" value="Save" />
			     	</form>
			    </div>
			</div>
		);
	}
}
</script>


<script type="text/babel">
	ReactDOM.render(<TodoApp/>, document.querySelector('.container'))
</script>