import { forwardRef, useEffect, useRef } from 'react';

export default forwardRef(function CreditCardInput({ type = 'text', className = '', isFocused = false, onChange, ...props }, ref) {
    const input = ref ? ref : useRef();

    function formatCreditCardNumber(value) {
        const onlyNums = value.replace(/\D/g, '');
        const limitedTo16Nums = onlyNums.slice(0, 16);
        return limitedTo16Nums.replace(/(\d{4})(?=\d)/g, '$1 ');
    }

    const handleChange = (event) => {
        const formattedValue = formatCreditCardNumber(event.target.value);
        if (onChange) {
            onChange({ ...event, target: { ...event.target, value: formattedValue } });
        }
    };

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    return (
        <input
            {...props}
            type={type}
            className={
                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ' +
                className
            }
            ref={input}
            onChange={handleChange}
        />
    );
});
