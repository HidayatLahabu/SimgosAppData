export function shuffleNumber(number) {
    const nikArray = number.split('');
    for (let i = nikArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [nikArray[i], nikArray[j]] = [nikArray[j], nikArray[i]];
    }
    return nikArray.join('');
}
