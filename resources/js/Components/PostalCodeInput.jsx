import { forwardRef, useEffect, useRef } from 'react';

export default forwardRef(function PostalCodeInput({ type = 'text', className = '', isFocused = false, onChange, ...props }, ref) {
    const input = ref ? ref : useRef();

    function formatPostalCode(value) {
        const digits = value.replace(/\D/g, '');
        const limitedDigits = digits.slice(0, 8);
        return limitedDigits.slice(0, 5) + (limitedDigits.length > 5 ? '-' + limitedDigits.slice(5) : '');
    }

    const handleChange = (event) => {
        const formattedValue = formatPostalCode(event.target.value);
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
