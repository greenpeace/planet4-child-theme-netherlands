import './styles/poster-makes.scss';
import posterMaker from './scripts/poster-maker.js';

posterMaker();
if (module.hot) {
  module.hot.accept('./scripts/poster-maker.js', function () {
    posterMaker();
  });
}
