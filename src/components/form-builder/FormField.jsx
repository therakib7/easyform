import { useState } from '@wordpress/element';

export default ({ data, rowI, colI, i, onRemove }) => {
	const onChange = () => {

	}
	const { type, label } = data;

	let field = '';
	switch (data.type) {
		case 'text':
			field = <input type="text" placeholder={''} />;
			break;
		case 'number':
			field = <input type="number" placeholder={''} />;
			break;
		case 'email':
			field = <input type="email" placeholder={''} />;
			break;
		case 'password':
			field = <input type="password" placeholder={''} />;
			break;
		case 'heading':
			field = <h3>this is heading</h3>;
			break;
		case 'paragraph':
			field = <p>this is paragraph</p>;
			break;
		default:
			field = <input type="text" placeholder={''} onChange={onChange} />;
	}

	return (
		<div className={'rhef-field' + type}>
			<button className="form-field_remove" onClick={() => onRemove(rowI, colI, i)}>
				X
			</button>
			<label>{label}</label>{field}
		</div>
	);
} 