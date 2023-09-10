import { useState, useEffect } from '@wordpress/element';
import './style.css'

// import api from 'api';

import FormField from './FormField';

const FormBuilder = () => {
	const [fields, setFields] = useState([]);
	const [canvas, setCanvas] = useState([]);
	const [loading, setLoading] = useState(false);
	const [draggedField, setDraggedField] = useState(null);

	useEffect(() => {
		//TODO: stop multiple rendering
		setLoading(true);
		/* api.getS('form-builders', 0 + '?admin=true').then(resp => {
			if (resp.data.success) {
				let data = resp.data.data;
				setFields(data.fields);
				setCanvas(data.canvas);
				setLoading(false);
			}
		}); */
	}, []);

	const handleDragStart = (e, field) => {
		setDraggedField(field);
	};

	const handleDragOver = (e) => {
		e.preventDefault();
	};

	const handleDrop = (e, rowI, colI) => {
		e.preventDefault();
		const newCanvas = [...canvas];
		newCanvas[rowI][colI].push(draggedField);
		setCanvas(newCanvas);
		setDraggedField(null);
	};

	const handleAddField = (rowI, colI) => {
		const newCanvas = [...canvas];
		newCanvas[rowI][colI].push({
			label: `${fields[0].fields[0].label}`,
			icon: `${fields[0].fields[0].icon}`,
			type: fields[0].fields[0].type,
		});
		setCanvas(newCanvas);
	};

	const handleAddCol = (rowI) => {
		const newCanvas = [...canvas];
		newCanvas[rowI].push([]);
		setCanvas(newCanvas);
	};

	const handleAddRow = () => {
		setCanvas([...canvas, []]);
	};

	const onFieldRemove = (rowI, colI, index) => {
		const newCanvas = [...canvas];
		newCanvas[rowI][colI].splice(index, 1);
		setCanvas(newCanvas);
	};

	return (
		<div className='form-builder'>
			<div className="form-sidebar">
				{fields.map((cat, i) => (
					<div
						key={i}
					>
						<h3>{cat.label}</h3>
						<div className='form-sidebar-fields'>
							{cat.fields.map((field, ii) => (
								<div
									key={ii}
									className="form-sidebar-field"
									draggable="true"
									onDragStart={(e) => handleDragStart(e, field)}
								>
									({field.icon}) {field.label}
								</div>
							))}
						</div>
					</div>
				))}
			</div>
			<div className="form-canvas">
				{canvas.map((row, rowI) => (
					<div className="form-row" key={rowI}>
						{row.map((col, colI) => (
							<div
								key={colI}
								className="form-column"
								onDragOver={handleDragOver}
								onDrop={(e) => handleDrop(e, rowI, colI)}
							>
								{col.map((field, i) => (
									<FormField
										key={i}
										data={field}
										rowI={rowI}
										colI={colI}
										i={i}
										onRemove={() => onFieldRemove(rowI, colI, i)}
									/>
								))}
								<button onClick={() => handleAddField(rowI, colI)}>
									Add Field
								</button>
							</div>
						))}
						<button onClick={() => handleAddCol(rowI)}>Add Column</button>
					</div>
				))}
				<button onClick={handleAddRow}>Add Row</button>
			</div>
		</div>
	);
};

export default FormBuilder;
