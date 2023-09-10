import { useState, useEffect } from '@wordpress/element';
import { NavLink } from "react-router-dom";
const FormList = () => {
	const [posts, setPost] = useState([]);
	useEffect(() => {
		// fetch('https://jsonplaceholder.typicode.com/posts')
		// 	.then(response => response.json())
		// 	.then(data => {
		// 		setPost(data)
		// 	})

	}, []);



	return (
		<div className='form-builder'>
			{/* <table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Content</th>
						<th>User ID</th>
					</tr>
				</thead>
				<tbody>
					{posts.map(({ id, title, body, userId }, i) => (
						<tr key={i}>
							<td>{id}</td>
							<td>{title}</td>
							<td>{body}</td>
							<td>{userId}</td>
						</tr>
					))}

				</tbody>
			</table> */}
			< NavLink to='/form-builder/new' >
				New Form
			</NavLink >
		</div >
	);
};

export default FormList;
