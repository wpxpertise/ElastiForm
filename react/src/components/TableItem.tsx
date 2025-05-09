import React, { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import Column from '../core/Column';
import Row from '../core/Row';
import EditCalendarIcon from '@mui/icons-material/EditCalendar';
import HighlightOffIcon from '@mui/icons-material/HighlightOff';
import RecyclingIcon from '@mui/icons-material/Recycling';
import DeleteSweepIcon from '@mui/icons-material/DeleteSweep';
import DeleteOutlineIcon from '@mui/icons-material/DeleteOutline';
import { CopyIcon, Cross, DeleteIcon, EditIcon, TrashCan } from '../icons';
//styles
import '../styles/_table_item.scss';
import Title from '../core/Title';
import { getNonce } from '../Helpers';
import Modal from './../core/Modal';

function TableItem({ table, setCopiedTables, setTableCount, setTables, setLoader }) {
	const confirmDeleteRef = useRef();
	const [copySuccess, setCopySuccess] = useState(false);
	const [deleteModal, setDeleteModal] = useState<boolean>(false);

	/**
	 * 
	 * @param id Copy Shortcode
	 */
	const handleCopyShortcode = async (id) => {
		const shortcode = `[simple_form id="${id}"]`;

		try {
			await navigator.clipboard.writeText(shortcode);
			setCopySuccess(true);

			// Reset copySuccess state after 1 second
			setTimeout(() => {
				setCopySuccess(false);
			}, 1000); // 1000 milliseconds = 1 second

		} catch (err) {
			setCopySuccess(false);
		}
	};

	/**
	 * Delete Modal close
	*/
	const handleClosePopup = () => {
		setDeleteModal(false);
	};

	/**
	 * Delete Modal
	*/
	const handleDeleteTable = () => {
		setDeleteModal(true);
	};

	/**
	 * Delete Modal close get confitmation and delete from DB
	*/
	const ConfirmDeleteTable = (id) => {
		wp.ajax.send('ElastiForm_delete_table', {
			data: {
				nonce: getNonce(),
				id,
			},
			success() {
				setDeleteModal(false);
				setLoader(true);

				wp.ajax.send('ElastiForm_get_tables', {
					data: {
						nonce: getNonce(),
					},
					success({ tables, tables_count }) {
						setTables(tables);
						setCopiedTables(tables);
						setTableCount(tables_count);
						setLoader(false);
					},
					error(error) {
						console.error(error);
					},
				});
			},
			error(error) {
				console.error(error);
			},
		});
	};

	/**
	 * Alert close if clicked on outside of element
	 *
	 * @param  event
	 */
	function handleCancelOutside(event: MouseEvent) {
		if (
			confirmDeleteRef.current &&
			!confirmDeleteRef.current.contains(event.target)
		) {
			handleClosePopup();
		}
	}

	useEffect(() => {
		document.addEventListener('mousedown', handleCancelOutside);
		return () => {
			document.removeEventListener('mousedown', handleCancelOutside);
		};
	}, [handleCancelOutside]);

	return (
		<div className="table_info-action_box_wrapper">
			{deleteModal && (
				<Modal>
					<div
						className="delete-table-modal-wrap modal-content"
						ref={confirmDeleteRef}
					>
						<div
							className="cross_sign"
							onClick={() => handleClosePopup()}
						>
							{/* {Cross} */}
							<HighlightOffIcon className='scf-delete-btn' />
						</div>
						<div className="delete-table-modal">
							{/* <div className="modal-media">{TrashCan}</div> */}
							<div className="modal-media"><RecyclingIcon fontSize='large' htmlColor='secondary' className='scf-form-delete' /></div>
							<h2>Are you sure to delete the Form? </h2>
							<div className="action-buttons">
								<button
									className="ElastiForm-button cancel-button"
									onClick={handleClosePopup}
								>
									Cancel
								</button>
								<button
									className="ElastiForm-button confirm-button"
									onClick={() =>
										ConfirmDeleteTable(table.id)
									}
								>
									Delete
								</button>
							</div>
						</div>
					</div>
				</Modal>
			)}

			<div className="table_info-action_box">
				<div className="table-info-box">
					<div className="table-info">
						{/* <Title tagName="h4">{table.form_name}</Title> */}
						<Link to={`/edit/${table.id}`} className="table-edit">
							<Title tagName="h4">{table.form_name}</Title>
						</Link>

						<Title tagName="p">Form ID : {table.id}</Title>
					</div>
				</div>
				<div className="table-action-box">
					<button
						className={`copy-shortcode btn-shortcode ${!copySuccess ? '' : 'btn-success'}`}
						onClick={() => handleCopyShortcode(table.id)}
					>
						{!copySuccess ? (
							<svg
								width="20"
								height="20"
								viewBox="0 0 14 14"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M12.6 0H5.6C4.8279 0 4.2 0.6279 4.2 1.4V4.2H1.4C0.6279 4.2 0 4.8279 0 5.6V12.6C0 13.3721 0.6279 14 1.4 14H8.4C9.1721 14 9.8 13.3721 9.8 12.6V9.8H12.6C13.3721 9.8 14 9.1721 14 8.4V1.4C14 0.6279 13.3721 0 12.6 0ZM1.4 12.6V5.6H8.4L8.4014 12.6H1.4ZM12.6 8.4H9.8V5.6C9.8 4.8279 9.1721 4.2 8.4 4.2H5.6V1.4H12.6V8.4Z"
									fill="#1E1E1E"
								/>
							</svg>
						) : (
							<svg
								width="20"
								height="20"
								viewBox="0 0 14 14"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M12.6 0H5.6C4.8279 0 4.2 0.6279 4.2 1.4V4.2H1.4C0.6279 4.2 0 4.8279 0 5.6V12.6C0 13.3721 0.6279 14 1.4 14H8.4C9.1721 14 9.8 13.3721 9.8 12.6V9.8H12.6C13.3721 9.8 14 9.1721 14 8.4V1.4C14 0.6279 13.3721 0 12.6 0ZM1.4 12.6V5.6H8.4L8.4014 12.6H1.4ZM12.6 8.4H9.8V5.6C9.8 4.8279 9.1721 4.2 8.4 4.2H5.6V1.4H12.6V8.4Z"
									fill="#FF7E47"
								/>
							</svg>
						)}
						Copy Shortcode
					</button>
					<Link
						to={`/edit/${table.id}`}
						className="table-edit"
					>
						{/* {EditIcon} */}
						<EditCalendarIcon className='sf-edit-form' />
					</Link>
					<button
						className="table-delete"
						onClick={handleDeleteTable}
					>
						{/* {DeleteIcon} */}
						<DeleteOutlineIcon className='scf-delete-btn' />
					</button>
				</div>
			</div>
		</div>
	);
}

export default TableItem;
