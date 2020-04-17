var objFilterTotalScore = new RangeFilter();
objFilterTotalScore.setKeyname("hyb-range-filter-totalscore"); //css-class with values
objFilterTotalScore.setHeadline("Punkte Gesamtbewertung");  
objFilterTotalScore.setContainerClassName('hyb-range-filter-totalscore');
objFilterTotalScore.setObjRegEx(/^(.*)$/);
objFilterTotalScore.setMinValue(1);
objFilterTotalScore.setMaxValue(10);
objFilterTotalScore.setStartMinValue(1);
objFilterTotalScore.setStartMaxValue(10);
objFilterTotalScore.setSliderSteps(1);
objFilterTotalScore.init();
objFilterTotalScore.printFilter();

var objFilterTotalVerarb = new RangeFilter();
objFilterTotalVerarb.setKeyname("hyb-range-filter-verarbeitung"); //css-class with values
objFilterTotalVerarb.setHeadline("Punkte für die Verarbeitung und Bedienbarkeit");  
objFilterTotalVerarb.setContainerClassName('hyb-range-filter-verarbeitung');
objFilterTotalVerarb.setObjRegEx(/^(.*)$/);
objFilterTotalVerarb.setMinValue(1);
objFilterTotalVerarb.setMaxValue(10);
objFilterTotalVerarb.setStartMinValue(1);
objFilterTotalVerarb.setStartMaxValue(10);
objFilterTotalVerarb.setSliderSteps(1);
objFilterTotalVerarb.init();
objFilterTotalVerarb.printFilter();

var objFilterTotalFotos = new RangeFilter();
objFilterTotalFotos.setKeyname("hyb-range-filter-fotos-und-videos"); //css-class with values
objFilterTotalFotos.setHeadline("Foto- und Videoqualität");  
objFilterTotalFotos.setContainerClassName('hyb-range-filter-fotos-und-videos');
objFilterTotalFotos.setObjRegEx(/^(.*)$/);
objFilterTotalFotos.setMinValue(1);
objFilterTotalFotos.setMaxValue(10);
objFilterTotalFotos.setStartMinValue(1);
objFilterTotalFotos.setStartMaxValue(10);
objFilterTotalFotos.setSliderSteps(1);
objFilterTotalFotos.init();
objFilterTotalFotos.printFilter();

var objFilterTotalZeit = new RangeFilter();
objFilterTotalZeit.setKeyname("hyb-range-filter-zeitverhalten"); //css-class with values
objFilterTotalZeit.setHeadline("Auslösegeschwindigkeiten von Fotos und Videos");  
objFilterTotalZeit.setContainerClassName('hyb-range-filter-zeitverhalten');
objFilterTotalZeit.setObjRegEx(/^(.*)$/);
objFilterTotalZeit.setMinValue(1);
objFilterTotalZeit.setMaxValue(10);
objFilterTotalZeit.setStartMinValue(1);
objFilterTotalZeit.setStartMaxValue(10);
objFilterTotalZeit.setSliderSteps(1);
objFilterTotalZeit.init();
objFilterTotalZeit.printFilter();