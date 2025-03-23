import React from 'react';

const Description = ( { children } ) => {
	const classes = () => {
		const c = 'ElastiForm-description';

		return c;
	};
	return <p className={ classes() }>{ children }</p>;
};

export default Description;
