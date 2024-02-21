
function best_calculator(prices, weights) {

    const _final = [];
    let i = 0;
    let _min = Infinity;
    let _best_price = 0;
    let _best_weight = 0;

    prices.forEach(e => {
    _final[i] = e/weights[i];
    
    if(_final[i] < _min){
        _min = _final[i];
        _best_price = e;
        _best_weight = weights[i];
    } 
    i++;
    });

    return ret = {_best_price, _best_weight, _min};

} module.exports = best_calculator;
